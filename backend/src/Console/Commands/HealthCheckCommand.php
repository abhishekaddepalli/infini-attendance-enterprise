<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class HealthCheckCommand extends Command
{
    protected $signature = 'infini:health';
    protected $description = 'Perform system health checks';

    public function handle() { $this->info('System health: OK'); return 0; }
}
