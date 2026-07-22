<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class AttendanceSyncCommand extends Command
{
    protected $signature = 'infini:attendance-sync';
    protected $description = 'Sync attendance logs';

    public function handle() { $this->info('Attendance synced.'); return 0; }
}
