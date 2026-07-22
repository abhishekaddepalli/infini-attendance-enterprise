<?php

namespace Infini\Attendance\Contracts;

interface AttendanceRepositoryInterface
{
    public function find(int $id);
    public function getTodayLogs();
}
