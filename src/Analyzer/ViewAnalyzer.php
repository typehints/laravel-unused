<?php

namespace TypeHints\Unused\Analyzer;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use TypeHints\Unused\Parser\Action\ClosureParser;
use TypeHints\Unused\Parser\Action\ControllerParser;
use TypeHints\Unused\Parser\Action\ParserActionInterface;
use TypeHints\Unused\Parser\ParserInterface;
use TypeHints\Unused\Parser\ViewParser;
use TypeHints\Unused\Transformers\ViewTransformer;

class ViewAnalyzer
{
    protected $router;

    protected $unusedViews = [];

    protected $usedViews = [];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function analyze(): ViewAnalyzer
    {
        $this->usedViews = $this->fetchUsedViews();

        $this->unusedViews = $this->fetchUnusedViews();

        return $this;
    }

    public function delete(string $view): void
    {
        $filesystem = new Filesystem();

        $view = str_replace('.', '/', $view);

        foreach (config('view.paths') as $path) {
            $viewPath = $path.'/'.$view.'.blade.php';

            if ($filesystem->exists($viewPath)) {
                $filesystem->delete($viewPath);
            }
        }
    }

    protected function fetchUnusedViews(): array
    {
        $flattenUsedViews = collect($this->usedViews)->map(function ($viewTransformer) {
            return array_keys($viewTransformer->getViews());
        })
        ->flatten();

        $this->retrieveViewCounts($flattenUsedViews);

        return array_diff(
            $this->getAllViews(),
            $flattenUsedViews->unique()->toArray()
        );
    }

    protected function fetchUsedViews(): array
    {
        return array_map(function ($route) {
            return $this->generateViewTransformer($route);
        }, $this->router->getRoutes()->getRoutes());
    }

    /**
     * @param Route $route
     *
     * @return TypeHints\Unused\Transformers\ViewTransformer
     */
    protected function generateViewTransformer(Route $route): ViewTransformer
    {
        return new ViewTransformer($this->parse($route));
    }

    /**
     * @return array
     */
    protected function getAllViews(): array
    {
        $filesystem = new Filesystem();

        $views = [];

        foreach (config('view.paths') as $path) {
            foreach ($filesystem->allFiles($path, true) as $file) {
                if (Str::startsWith($file->getRelativePath(), 'vendor')) {
                    break;
                }

                $views[] = str_replace('.blade.php', '', str_replace('/', '.', $file->getRelativePathname()));
            }
        }

        return $views;
    }

    /**
     * @param Route $route
     *
     * @return TypeHints\Unused\Parser\ViewParser
     */
    protected function parse(Route $route): ViewParser
    {
        return $this->viewParser(
            $this->actionParser($route)
        )->parse();
    }

    /**
     * @param Route $route
     *
     * @return TypeHints\Unused\Parser\Action\ParserActionInterface
     */
    protected function actionParser(Route $route): ParserActionInterface
    {
        if (array_key_exists('controller', $route->getAction())) {
            return $this->controllerParser($route)->parse();
        }

        return $this->closureParser($route)->parse();
    }

    /**
     * @param Route $route
     *
     * @return TypeHints\Unused\Parser\Action\ParserActionInterface
     */
    protected function controllerParser(Route $route): ParserActionInterface
    {
        return new ControllerParser($route);
    }

    /**
     * @param Route $route
     *
     * @return TypeHints\Unused\Parser\Action\ParserActionInterface
     */
    protected function closureParser(Route $route): ParserActionInterface
    {
        return new ClosureParser($route);
    }

    /**
     * @param ParserActionInterface $content
     *
     * @return TypeHints\Unused\Parser\ParserInterface
     */
    protected function viewParser(ParserActionInterface $content): ParserInterface
    {
        return new ViewParser($content);
    }

    /**
     * @return array
     */
    public function getUnusedViews(): array
    {
        return array_values($this->unusedViews);
    }

    /**
     * @return array
     */
    public function getUsedViews(): array
    {
        return array_map(function ($usedView) {
            return $usedView->transform();
        }, $this->usedViews);
    }

    /**
     * @param $usedViews
     */
    public function retrieveViewCounts(Collection $usedViews): void
    {
        $usedViewCountValues = array_count_values($usedViews->toArray());

        collect($this->usedViews)->map(function ($viewTransformer) use ($usedViewCountValues) {
            $views = array_keys($viewTransformer->getViews());

            if (!$views) {
                return;
            }

            foreach ($views as $view) {
                $viewTransformer->setViewCount($view, $usedViewCountValues[$view]);
            }
        });
    }
}
