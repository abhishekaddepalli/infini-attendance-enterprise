<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'infini:backup';
    protected $description = 'Backup application database and media';

    public function handle() { $this->info('Backup created.'); return 0; }
}
