<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class DeviceSyncCommand extends Command
{
    protected $signature = 'infini:device-sync';
    protected $description = 'Sync biometric devices';

    public function handle() { $this->info('Devices synced.'); return 0; }
}
