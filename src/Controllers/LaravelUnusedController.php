<?php

namespace TypeHints\Unused\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Router;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;
use TypeHints\Unused\Analyzer\ViewAnalyzer;

class LaravelUnusedController extends Controller
{
    protected $viewAnalyzer;

    public function __construct(ViewAnalyzer $viewAnalyzer)
    {
        $this->viewAnalyzer = $viewAnalyzer;
    }

    public function __invoke()
    {
        $viewAnalyzer = $this->viewAnalyzer->analyze();

        return view('laravelunused::dashboard', [
            'usedViews' => $viewAnalyzer->getUsedViews(),
            'unusedViews' => $viewAnalyzer->getUnusedViews(),
            'laravelUnusedScriptVariables' => config('laravelunused')
        ]);
    }

    public function delete(Request $request)
    {
        $this->viewAnalyzer->delete($request->view);

        return response()->json('success');
    }
}
