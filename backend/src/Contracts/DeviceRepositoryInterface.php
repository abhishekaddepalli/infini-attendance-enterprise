<?php

namespace Infini\Attendance\Contracts;

interface DeviceRepositoryInterface
{
    public function find(int $id);
    public function all();
}
