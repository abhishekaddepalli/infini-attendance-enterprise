<?php

namespace Infini\Attendance\Repositories;

use Infini\Attendance\Contracts\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function find(int $id) { return null; }
    public function all() { return []; }
}
