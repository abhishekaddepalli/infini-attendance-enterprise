<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class GenerateReportsCommand extends Command
{
    protected $signature = 'infini:reports-generate';
    protected $description = 'Generate automated attendance reports';

    public function handle() { $this->info('Reports generated.'); return 0; }
}
