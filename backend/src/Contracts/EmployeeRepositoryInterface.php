<?php

namespace Infini\Attendance\Contracts;

interface EmployeeRepositoryInterface
{
    public function find(int $id);
    public function all();
}
