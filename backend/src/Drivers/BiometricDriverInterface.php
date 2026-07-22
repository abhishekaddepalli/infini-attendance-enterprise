<?php

declare(strict_types=1);

namespace Infini\Attendance\Drivers;

interface BiometricDriverInterface
{
    public function connect(string $host, int $port, array $options = []): mixed;
    public function disconnect(mixed $connection): void;
    public function getAttendanceLogs(mixed $connection, \DateTime $from, \DateTime $to): array;
    public function uploadUsers(mixed $connection, array $users): array;
    public function uploadFaces(mixed $connection, array $faces): array;
    public function uploadFingerprints(mixed $connection, array $fingerprints): array;
    public function uploadCards(mixed $connection, array $cards): array;
    public function getDeviceInfo(mixed $connection): array;
    public function getDeviceLogs(mixed $connection, \DateTime $from, \DateTime $to): array;
    public function restart(mixed $connection): bool;
    public function configure(mixed $connection, array $settings): bool;
    public function clearLogs(mixed $connection, ?\DateTime $before = null): bool;
    public function getRealTimeEvent(mixed $connection): ?array;
    public function verifyFingerprint(mixed $connection, string $employeeId, string $fingerprintData): array;
    public function supportsFeature(string $feature): bool;
    public function getCapabilities(): array;
    public function getBrand(): string;
    public function getVersion(): string;
}
