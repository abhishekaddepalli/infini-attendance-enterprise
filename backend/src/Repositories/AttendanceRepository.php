<?php

namespace Infini\Attendance\Repositories;

use Infini\Attendance\Contracts\AttendanceRepositoryInterface;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function find(int $id) { return null; }
    public function getTodayLogs() { return []; }
}
