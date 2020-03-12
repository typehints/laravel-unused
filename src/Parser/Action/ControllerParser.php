<?php

namespace TypeHints\Unused\Parser\Action;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\ControllerDispatcher;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use TypeHints\Unused\Parser\ViewParser;
use ReflectionClass;
use ReflectionMethod;

class ControllerParser implements ParserActionInterface
{
    /**
     * @var Route
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
     * @return TypeHints\Unused\Parser\Action\ControllerParser
     */
    public function parse() : ParserActionInterface
    {
        $this->content = $this->fetchContent();

        return $this;
    }

    /**
     * @return array
     */
    protected function fetchContent() : ?array
    {
        if ($this->isViewController()) {
            return $this->resolveViewControllerInvokeMethod();
        }

        $method = $this->resolveMethod();

        if (! $method) {
            return null;
        }

        return array_slice(
            file($method->getFileName()),
            $method->getStartLine() - 1,
            $method->getEndLine() - $method->getStartLine() + 1
        );
    }

    /**
     * @return ReflectionMethod
     */
    protected function resolveMethod() : ?ReflectionMethod
    {
        try {
            return (new ReflectionClass($this->getName()))
                ->getMethod($this->getMethod());
        } catch (\Exception $exception) {
            if ($exception->getCode() === -1) {
                $this->errors[] = "{$this->getName()} Controller does not exists !";

                return null;
            }

            $this->errors[] = "{$this->getMethod()} Method does not exists on {$this->getName()} !";

            return null;
        }
    }

    /**
     * @return boolean
     */
    public function isViewController() : bool
    {
        return strpos($this->getName(), 'ViewController') !== false;
    }

    /**
     * @return array
     */
    public function resolveViewControllerInvokeMethod() : array
    {
        $this->route->bind(new Request);

        $params = $this->route->parametersWithoutNulls();

        if (array_key_exists('view', $params)) {
            return [
                'view' => $params['view']
            ];
        }

        return $this->route->parametersWithoutNulls();
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return Arr::last(explode('@', $this->route->getAction('uses')));
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return Arr::first(explode('@', $this->route->getAction('controller')));
    }

    /**
     * @return array
     */
    public function getContent() : ?array
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getErrors() : ?array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getRoute() : ?array
    {
        return array_merge($this->route->getAction(), [
            'methods' => $this->route->methods(),
            'uri' => $this->route->uri
        ]);
    }
}
