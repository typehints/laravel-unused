<?php

namespace TypeHints\Unused\Tests\Parser;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use TypeHints\Unused\Parser\Action\ControllerParser;
use TypeHints\Unused\Tests\TestCase;

class ControllerParserTest extends TestCase
{
    /** @test */
    public function can_get_content()
    {
        $controllerParser = new ControllerParser($this->routeClosureExample());

        $controllerParser->parse();

        $controllerContent = $controllerParser->getContent();

        $controllerContent = str_replace(' ', '', trim(implode('', $controllerContent)));

        $this->assertEquals("publicfunctionindex()
{
returnview('welcome');
}", $controllerContent);
    }

    /** @test */
    public function can_get_content_when_controller_is_view_analyzer()
    {
        $controllerParser = new ControllerParser($this->routeViewControllerExample());

        $controllerParser->parse();

        $controllerContent = $controllerParser->getContent();

        $this->assertEquals('welcome', $controllerContent['view']);
    }

    protected function routeClosureExample()
    {
        return new Route(['GET'], '/', [
            'uses'       => 'TypeHints\\Unused\\Tests\\Parser\\ExampleTestsController@index',
            'controller' => 'TypeHints\\Unused\\Tests\\Parser\\ExampleTestsController',
        ]);
    }

    protected function routeViewControllerExample()
    {
        $route = new Route(['GET'], '/', [
            'uses'       => '\Illuminate\Routing\ViewController@__invoke',
            'controller' => '\Illuminate\Routing\ViewController',
        ]);

        $route->bind(new Request());

        $route->defaults('view', 'welcome');

        return $route;
    }
}

class ExampleTestsController
{
    public function index()
    {
        return view('welcome');
    }
}
