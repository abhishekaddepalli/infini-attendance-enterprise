<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class LeaveResetCommand extends Command
{
    protected $signature = 'infini:leave-reset';
    protected $description = 'Reset annual leave balances';

    public function handle() { $this->info('Leave balances reset.'); return 0; }
}
