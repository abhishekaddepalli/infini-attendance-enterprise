<?php

namespace Infini\Attendance\Services;

class PayrollEngine
{
    public function calculateSalary(int $employeeId, string $month): array
    {
        return [
            'gross' => 5000,
            'deductions' => 500,
            'net' => 4500
        ];
    }
}
