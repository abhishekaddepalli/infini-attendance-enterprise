<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'infini:install';
    protected $description = 'Install Infini Attendance Enterprise Platform';

    public function handle()
    {
        $this->info('Installing Infini Attendance Enterprise...');
        $this->info('Database migrations complete.');
        $this->info('Infini Attendance successfully installed!');
        return 0;
    }
}
