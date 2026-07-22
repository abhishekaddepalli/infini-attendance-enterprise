<?php

namespace Infini\Attendance\Services;

class NotificationService
{
    public function send(string $recipient, string $message, string $channel = 'push'): bool { return true; }
}
