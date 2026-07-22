<?php

declare(strict_types=1);

namespace Infini\Attendance\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Infini\Attendance\Contracts\AttendanceRepositoryInterface;
use Infini\Attendance\Exceptions\AttendanceException;
use Infini\Attendance\Models\Attendance;

class AttendanceEngine
{
    private array $methodHandlers = [
        'face_recognition' => 'handleFaceRecognition',
        'fingerprint' => 'handleFingerprint',
        'rfid' => 'handleRfid',
        'nfc' => 'handleNfc',
        'qr_code' => 'handleQrCode',
        'dynamic_qr' => 'handleDynamicQr',
        'gps' => 'handleGps',
        'geofence' => 'handleGeofence',
        'wifi' => 'handleWifi',
        'ip' => 'handleIp',
        'bluetooth_beacon' => 'handleBluetooth',
        'manual' => 'handleManual',
        'manager_approval' => 'handleManagerApproval',
        'selfie' => 'handleSelfie',
        'voice' => 'handleVoice',
    ];

    public function __construct(
        private readonly AttendanceRepositoryInterface $attendanceRepo,
        private readonly FaceRecognitionService $faceService,
        private readonly GeofenceService $geofenceService,
        private readonly ShiftScheduler $shiftScheduler,
        private readonly NotificationService $notificationService,
        private readonly AuditService $auditService,
    ) {}

    public function checkIn(array $data): Attendance
    {
        $method = $data['method'];
        if (!isset($this->methodHandlers[$method])) {
            throw AttendanceException::invalidMethod($method);
        }

        $this->preventDuplicateAttendance($data['employee_id']);

        $handler = $this->methodHandlers[$method];
        $validatedData = $this->$handler($data);

        $shift = $this->shiftScheduler->getCurrentShift($data['employee_id']);
        $attendanceType = $this->determineType($validatedData, $shift);
        $status = $this->calculateStatus($validatedData, $shift);

        $attendance = $this->attendanceRepo->create([
            'tenant_id' => $data['tenant_id'],
            'employee_id' => $data['employee_id'],
            'shift_id' => $shift?->id,
            'check_in' => $validatedData['timestamp'] ?? now(),
            'check_in_method' => $method,
            'check_in_location' => $validatedData['location'] ?? null,
            'check_in_device' => $validatedData['device_id'] ?? null,
            'attendance_type' => $attendanceType,
            'status' => $status,
            'face_confidence' => $validatedData['face_confidence'] ?? null,
            'gps_accuracy' => $validatedData['gps_accuracy'] ?? null,
            'geofence_status' => $validatedData['geofence_status'] ?? null,
            'spoof_check_passed' => $validatedData['spoof_check_passed'] ?? null,
            'liveness_check_passed' => $validatedData['liveness_check_passed'] ?? null,
            'metadata' => $validatedData['metadata'] ?? [],
        ]);

        Cache::tags(['attendance', "employee:{$data['employee_id']}"])->flush();

        event(new \Infini\Attendance\Events\AttendanceCheckedIn($attendance));
        $this->notificationService->sendAttendanceNotification($attendance);
        $this->auditService->log('attendance.check_in', $attendance->toArray());

        return $attendance;
    }

    public function checkOut(int $employeeId, array $data = []): Attendance
    {
        $attendance = $this->attendanceRepo->findActiveByEmployee($employeeId);
        if (!$attendance) {
            throw AttendanceException::noActiveCheckIn();
        }

        $checkOutTime = $data['timestamp'] ?? now();
        $totalMinutes = Carbon::parse($attendance->check_in)->diffInMinutes($checkOutTime);
        $overtimeMinutes = max(0, $totalMinutes - (($attendance->shift?->daily_hours ?? 8) * 60));

        $attendance = $this->attendanceRepo->update($attendance->id, [
            'check_out' => $checkOutTime,
            'check_out_method' => $data['method'] ?? $attendance->check_in_method,
            'total_hours' => round($totalMinutes / 60, 2),
            'total_minutes' => $totalMinutes,
            'overtime_minutes' => $overtimeMinutes,
        ]);

        if ($overtimeMinutes > 30) {
            dispatch(new \Infini\Attendance\Jobs\ProcessOvertime($attendance->id, $overtimeMinutes));
        }

        event(new \Infini\Attendance\Events\AttendanceCheckedOut($attendance));
        $this->auditService->log('attendance.check_out', $attendance->toArray());

        return $attendance;
    }

    protected function handleFaceRecognition(array $data): array
    {
        $result = $this->faceService->verifyWithLiveness(
            $data['employee_id'],
            $data['face_image'],
            ['blink_detection' => $data['blink_detected'] ?? false]
        );

        if (!$result['verified']) {
            event(new \Infini\Attendance\Events\AttendanceFlagged($data['employee_id'], 'face_failed'));
            throw AttendanceException::faceVerificationFailed($result['reason']);
        }

        return [
            'timestamp' => now(),
            'face_confidence' => $result['confidence'],
            'spoof_check_passed' => $result['spoof_check_passed'],
            'liveness_check_passed' => $result['liveness_check_passed'],
            'metadata' => ['face_match_id' => $result['match_id'] ?? null],
        ];
    }

    protected function handleGps(array $data): array
    {
        $location = [
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'accuracy' => $data['gps_accuracy'] ?? 0,
        ];

        $geofenceResult = $this->geofenceService->checkLocation(
            $data['employee_id'], $location, $data['tenant_id']
        );

        if (!$geofenceResult['within_geofence'] && config('infini.geofence.strict_mode')) {
            throw AttendanceException::outsideGeofence($geofenceResult['distance'] ?? 0);
        }

        return [
            'timestamp' => now(),
            'location' => $location,
            'gps_accuracy' => $location['accuracy'],
            'geofence_status' => $geofenceResult['within_geofence'] ? 'inside' : 'outside',
            'metadata' => ['geofence_id' => $geofenceResult['geofence_id'] ?? null],
        ];
    }

    protected function handleQrCode(array $data): array
    {
        // Validate dynamic QR code
        if (empty($data['qr_data'])) {
            throw AttendanceException::missingQrData();
        }
        return ['timestamp' => now(), 'metadata' => ['qr_id' => $data['qr_data']]];
    }

    protected function handleFingerprint(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['finger_index' => $data['finger_index'] ?? null]];
    }

    protected function handleRfid(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['rfid_tag' => $data['rfid_tag'] ?? null]];
    }

    protected function handleNfc(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['nfc_uid' => $data['nfc_data'] ?? null]];
    }

    protected function handleDynamicQr(array $data): array
    {
        return $this->handleQrCode($data);
    }

    protected function handleGeofence(array $data): array
    {
        return $this->handleGps($data);
    }

    protected function handleWifi(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['wifi_ssid' => $data['wifi_ssid'] ?? null]];
    }

    protected function handleIp(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['ip' => $data['ip_address'] ?? request()->ip()]];
    }

    protected function handleBluetooth(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['beacon_id' => $data['beacon_id'] ?? null]];
    }

    protected function handleManual(array $data): array
    {
        if (empty($data['approved_by'])) {
            throw AttendanceException::manualAttendanceRequiresApproval();
        }
        return ['timestamp' => Carbon::parse($data['timestamp'] ?? now())];
    }

    protected function handleManagerApproval(array $data): array
    {
        return $this->handleManual($data);
    }

    protected function handleSelfie(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['selfie_url' => $data['selfie_url'] ?? null]];
    }

    protected function handleVoice(array $data): array
    {
        return ['timestamp' => now(), 'metadata' => ['voice_hash' => $data['voice_hash'] ?? null]];
    }

    protected function preventDuplicateAttendance(int $employeeId): void
    {
        $existing = $this->attendanceRepo->findActiveByEmployee($employeeId);
        if ($existing) {
            throw AttendanceException::alreadyCheckedIn(
                Carbon::parse($existing->check_in)->diffForHumans()
            );
        }
    }

    protected function determineType(array $data, $shift): string
    {
        if ($data['is_remote'] ?? false) return 'remote';
        if ($data['is_field_work'] ?? false) return 'field';
        return 'full_day';
    }

    protected function calculateStatus(array $data, $shift): string
    {
        if (!$shift || empty($data['timestamp'])) return 'present';
        $timestamp = Carbon::parse($data['timestamp']);
        $gracePeriod = config('infini.attendance.grace_period', 15);
        $shiftStart = Carbon::parse($shift->start_time);

        if ($timestamp->format('H:i') <= $shiftStart->format('H:i')) return 'on_time';
        if ($timestamp->diffInMinutes($shiftStart) <= $gracePeriod) return 'grace';
        return 'late';
    }

    public function getTodaySummary(int $tenantId): array
    {
        return Cache::tags(['attendance', "tenant:{$tenantId}"])->remember(
            "summary:{$tenantId}:" . now()->toDateString(),
            60,
            fn() => [
                'present' => $this->attendanceRepo->countPresentToday($tenantId),
                'absent' => $this->attendanceRepo->countAbsentToday($tenantId),
                'on_leave' => $this->attendanceRepo->countOnLeaveToday($tenantId),
                'late' => $this->attendanceRepo->countLateToday($tenantId),
                'remote' => $this->attendanceRepo->countRemoteToday($tenantId),
                'not_marked' => $this->attendanceRepo->countNotMarkedToday($tenantId),
            ]
        );
    }

    public function getLiveAttendance(int $tenantId): array
    {
        return $this->attendanceRepo->getLiveAttendance($tenantId)
            ->map(fn($a) => [
                'employee_id' => $a->employee_id,
                'employee_name' => $a->employee->full_name,
                'employee_photo' => $a->employee->photo_url,
                'department' => $a->employee->department?->name,
                'check_in' => $a->check_in,
                'method' => $a->check_in_method,
                'status' => $a->status,
            ])
            ->toArray();
    }
}
