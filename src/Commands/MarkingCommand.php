<?php

namespace TLabsCo\LaravelMarking\Commands;

use Illuminate\Console\Command;

class MarkingCommand extends Command
{
    public $signature = 'mark:laravel-marking';

    public $description = 'Laravel Marking command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
