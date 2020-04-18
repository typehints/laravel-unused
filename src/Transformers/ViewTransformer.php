<?php

namespace TypeHints\Unused\Transformers;

use TypeHints\Unused\Parser\ViewParser;

class ViewTransformer
{
    /**
     * @var ViewParser
     */
    protected $viewParser;

    /**
     * @var array
     */
    protected $views = [];

    /**
     * @param TypeHints\Unused\Parser\ViewParser $viewParser
     */
    public function __construct(ViewParser $viewParser)
    {
        $this->viewParser = $viewParser;

        $this->views = $this->viewParser->getViews();
    }

    public function transform() : array
    {
        return [
            'route'    => $this->getRoute(),
            'views'    => $this->getViews(),
            'children' => $this->viewParser->getChildren(),
            'parent'   => $this->getParent(),
            'action'   => $this->getActionContent(),
            'errors'   => $this->getErrors(),
        ];
    }

    /**
     * @return array
     */
    public function getViews(): array
    {
        return $this->views;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->viewParser->getAction()->getErrors();
    }

    /**
     * @return string
     */
    public function getActionContent() : ?string
    {
        $actionContent = $this->viewParser->getAction()->getContent();

        if ($actionContent) {
            return trim(implode("\n", $this->viewParser->getAction()->getContent()));
        }

        return null;
    }

    /**
     * @return array
     */
    public function getParent(): array
    {
        return $this->viewParser->getParent();
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return $this->viewParser->getAction()->getRoute();
    }

    /**
     * @param string $view
     * @param int    $count
     */
    public function setViewCount(string $view, int $count) : void
    {
        $this->views[$view] = $count;
    }
}
