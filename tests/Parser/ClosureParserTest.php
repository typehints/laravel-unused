<?php

namespace TypeHints\Unused\Tests\Parser;

use Illuminate\Routing\Route;
use TypeHints\Unused\Parser\Action\ClosureParser;
use TypeHints\Unused\Tests\TestCase;

class ClosureParserTest extends TestCase
{
    /** @test */
    public function can_get_closure_content()
    {
        $closureParser = new ClosureParser($this->routeClosureExample());

        $closureParser->parse();

        $closureContent = $closureParser->getContent();

        $closureContent = str_replace(' ', '', trim(implode('', $closureContent)));

        $this->assertEquals("function(){
returnview('welcome');
}", $closureContent);
    }

    protected function routeClosureExample()
    {
        return new Route(['GET'], '/', [
            'uses' =>
                function () {
                    return view('welcome');
                }
            ,
        ]);
    }
}
