<?php

namespace TypeHints\Unused\Parser\Action;

use Illuminate\Routing\Route;
use ReflectionFunction;

class ClosureParser implements ParserActionInterface
{
    /**
     * @var Illuminate\Routing\Route
     */
    protected $route;

    /**
     * @var array
     */
    protected $content = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @return TypeHints\Unused\Parser\Action\ParserActionInterface
     */
    public function parse() : ParserActionInterface
    {
        $this->content = $this->fetchContent();

        return $this;
    }

    /**
     * @return ReflectionFunction
     */
    protected function resolveMethod() : ReflectionFunction
    {
        return new ReflectionFunction($this->getClosure());
    }

    /**
     * @return array
     */
    protected function fetchContent() : array
    {
        $method = $this->resolveMethod();

        return array_slice(
            file($method->getFileName()),
            $method->getStartLine() - 1,
            $method->getEndLine() - $method->getStartLine() + 1
        );
    }

    /**
     * @return array
     */
    public function getContent() : array
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * @return callable
     */
    public function getClosure() : callable
    {
        return $this->route->getAction('uses');
    }

    /**
     * @return array
     */
    public function getRoute() : array
    {
        return array_merge($this->route->getAction(), [
            'methods' => $this->route->methods(),
            'uri' => $this->route->uri
        ]);
    }
}
