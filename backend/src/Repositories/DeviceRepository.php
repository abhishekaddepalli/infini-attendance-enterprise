<?php

namespace Infini\Attendance\Repositories;

use Infini\Attendance\Contracts\DeviceRepositoryInterface;

class DeviceRepository implements DeviceRepositoryInterface
{
    public function find(int $id) { return null; }
    public function all() { return []; }
}
