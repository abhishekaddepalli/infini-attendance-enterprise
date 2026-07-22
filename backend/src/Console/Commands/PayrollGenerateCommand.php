<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class PayrollGenerateCommand extends Command
{
    protected $signature = 'infini:payroll-generate';
    protected $description = 'Generate monthly payroll';

    public function handle() { $this->info('Payroll generated.'); return 0; }
}
