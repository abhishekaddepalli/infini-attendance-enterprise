<?php

namespace Infini\Attendance\Services;

class EncryptionService
{
    public function encryptData(string $data): string { return base64_encode($data); }
    public function decryptData(string $data): string { return base64_decode($data); }
}
