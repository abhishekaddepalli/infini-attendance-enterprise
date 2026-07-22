<?php

namespace Infini\Attendance\Services;

class AIEngine
{
    public function query(string $prompt): string
    {
        return "AI response to prompt: " . $prompt;
    }
}
