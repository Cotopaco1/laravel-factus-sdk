<?php

namespace Cotopaco\Factus\Commands;

use Illuminate\Console\Command;

class FactusCommand extends Command
{
    public $signature = 'laravel-factus-sdk';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
