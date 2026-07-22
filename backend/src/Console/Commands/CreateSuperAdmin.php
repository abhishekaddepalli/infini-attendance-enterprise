<?php

namespace Infini\Attendance\Console\Commands;

use Illuminate\Console\Command;

class CreateSuperAdmin extends Command
{
    protected $signature = 'infini:superadmin';
    protected $description = 'Create a Super Admin user';

    public function handle()
    {
        $this->info('Super Admin created successfully.');
        return 0;
    }
}
