<?php

namespace TypeHints\Unused\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TypeHints\Unused\Analyzer\ViewAnalyzer;

class CheckForUnusedViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:unused-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for unused views';

    /**
     * @var ViewAnalyzer
     */
    protected $viewAnalyzer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ViewAnalyzer $viewAnalyzer)
    {
        parent::__construct();

        $this->viewAnalyzer = $viewAnalyzer->analyze();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkForUnusedViews($input, $output);

        $output->writeln('✨ See something that needs to be improved? <options=bold>Create an issue</> or send us a <options=bold>pull request</>: <fg=cyan;options=bold>https://github.com/typehints/laravel-unused</>');
    }

    public function checkForUnusedViews(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $unusedViews = array_map(function ($view) {
            return [$view];
        }, $this->viewAnalyzer->getUnusedViews());

        if (!$unusedViews) {
            $io->success('We detected '. sizeof($unusedViews) .' unused views!');
            return;
        }

        $io->error('We detected '. sizeof($unusedViews) .' unused views!');

        $table = new Table($output);
        $table->setHeaders(['Unused Views'])
            ->setRows($unusedViews);

        $table->render();
    }
}
