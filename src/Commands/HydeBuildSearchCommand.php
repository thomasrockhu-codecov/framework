<?php

namespace Hyde\Framework\Commands;

use Hyde\Framework\Hyde;
use Hyde\Framework\Actions\GeneratesDocumentationSearchIndexFile;
use LaravelZero\Framework\Commands\Command;

/**
 * Hyde Command to run the Build Process for the DocumentationSearchIndex.
 *
 * @see \Tests\Feature\Commands\HydeBuildSearchCommandTest
 */
class HydeBuildSearchCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'build:search';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate the docs/search.json';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $actionTime = microtime(true);

        $this->comment('Generating documentation site search index...');
		GeneratesDocumentationSearchIndexFile::run();
        $this->line(' > Created <info>'.GeneratesDocumentationSearchIndexFile::$filePath.'</> in '.
            $this->getExecutionTimeInMs($actionTime)."ms\n");

        return 0;
    }

    protected function getExecutionTimeInMs(float $timeStart): float
    {
        return number_format(((microtime(true) - $timeStart) * 1000), 2);
    }
}
