<?php

namespace TypeHints\Unused\Parser;

use Illuminate\Filesystem\Filesystem;
use TypeHints\Unused\Parser\Action\ParserActionInterface;

class ViewParser implements ParserInterface
{
    /**
     * @var ParserActionInterface
     */
    protected $action;

    /**
     * @var array
     */
    protected $parent = [];

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var array
     */
    protected $childrenViews = [];

    /**
     * @var array
     */
    protected $viewAliases = [
        'View::make(',
        'view(',
        'view->make(',
    ];

    /**
     * @var array
     */
    protected $ignoredStrings = [
        '(',
        ')',
        ';',
        "'",
    ];

    /**
     * @var array
     */
    protected $bladeDirectives = [
        '@include(',
        '@includeIf(',
        '@extends(',
        'Blade::include(',
    ];

    /**
     * @var array
     */
    protected $statementBladeDirectives = [
        '@includeWhen(',
        '@includeUnless(',
        '@includeFirst(',
    ];

    /**
     * @param ParserActionInterface
     */
    public function __construct(ParserActionInterface $action)
    {
        $this->action = $action;
    }

    /**
     * @return ParserInterface
     */
    public function parse(): ParserInterface
    {
        $this->parent = $this->retrieveViewsFromMethod();

        if ($this->parent) {
            $this->retrieveChildrenFromNestedViews();
        }

        return $this;
    }

    public function retrieveChildrenFromNestedViews(): void
    {
        $this->children = $this->loopForNestedViews($this->parent);

        $this->resolveChildrenHierarchy($this->children);
    }

    /**
     * @param array $children
     */
    public function resolveChildrenHierarchy(array $children): void
    {
        collect($children)->each(function ($value, $key) {
            if (is_string($key)) {
                $this->childrenViews[] = $key;
            }

            return $this->resolveChildrenHierarchy($value);
        });
    }

    public function loopForNestedViews($views): array
    {
        $generated = [];

        if (!is_array($views)) {
            return $this->loopForNestedViews($this->retrieveNestedViews($views));
        }

        foreach ($views as $view) {
            if (!empty($this->loopForNestedViews($view))) {
                $generated[$view][] = $this->loopForNestedViews($view);
            } else {
                $generated[$view] = $this->loopForNestedViews($view);
            }
        }

        return $generated;
    }

    /**
     * @return array
     */
    protected function retrieveViewsFromMethod(): array
    {
        $views = [];

        $content = $this->action->getContent();

        if (!$content) {
            return [];
        }

        if (array_key_exists('view', ($content))) {
            $views[] = $content['view'];

            return $views;
        }

        foreach ($content as $key => $line) {
            foreach ($this->viewAliases as $viewAlias) {
                if (strpos($line, $viewAlias) !== false) {
                    $view = $this->getViewFromLine($line, $viewAlias);

                    if (empty($view)) {
                        $view = $this->getViewFromLine($content[$key + 1], $viewAlias);
                    }

                    $views[] = $this->retrieveViewFromLine($view, $viewAlias);
                }
            }
        }

        return $views;
    }

    protected function getViewFromLine($line, $viewAlias)
    {
        $line = trim($line);

        if (strpos($line, $viewAlias) === false) {
            return $line;
        }

        return substr($line, strpos($line, $viewAlias) + strlen($viewAlias));
    }

    /**
     * @param string $line
     * @param string $viewAlias
     *
     * @return string
     */
    protected function retrieveViewFromLine(string $view, string $viewAlias): string
    {
        if (strpos($view, ')') !== false) {
            $view = substr($view, 0, strpos($view, ')'));
        }

        if (($position = strpos($view, ',')) !== false) {
            $view = substr($view, 0, $position);
        }

        foreach ($this->ignoredStrings as $string) {
            $view = str_replace($string, '', $view);
        }

        return trim($view);
    }

    /**
     * @param string $view
     *
     * @return array
     */
    protected function retrieveNestedViews(string $view): array
    {
        $views = [];

        $content = $this->getViewContent($view);

        foreach ($this->bladeDirectives as $key => $bladeDirective) {
            $positions = $this->getPositionOfBladeDirectives($bladeDirective, $content);

            foreach ($positions as $position) {
                $view = $this->getViewFromLine($content, $bladeDirective);

                $views[] = $this->retrieveViewFromLine($view, $bladeDirective);
            }
        }

        return $views;
    }

    /**
     * @param string $bladeDirective
     * @param string $content
     *
     * @return array
     */
    protected function getPositionOfBladeDirectives(string $bladeDirective, ?string $content): array
    {
        $positions = [];

        $lastPos = 0;

        while (($lastPos = strpos($content, $bladeDirective, $lastPos)) !== false) {
            $positions[] = $lastPos;
            $lastPos = $lastPos + strlen($bladeDirective);
        }

        return $positions;
    }

    /**
     * @param string $view
     *
     * @return string
     */
    public function getViewContent(string $view): ?string
    {
        $filesystem = new Filesystem();

        foreach (config('view.paths') as $viewPath) {
            $path = $viewPath.'/'.str_replace('.', '/', $view).'.blade.php';

            if (!$filesystem->exists($path)) {
                // View Is Missing
                break;
            }

            return $filesystem->get($path);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getViews(): array
    {
        return collect(array_merge($this->childrenViews, $this->parent))
            ->unique()
            ->flatMap(function ($view) {
                return [
                    $view => 0,
                ];
            })->toArray();
    }

    /**
     * @return ParserActionInterface
     */
    public function getAction(): ParserActionInterface
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParent(): array
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}
