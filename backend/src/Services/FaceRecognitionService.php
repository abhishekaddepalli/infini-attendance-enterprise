<?php

namespace Infini\Attendance\Services;

class FaceRecognitionService
{
    public function verifyFace(string $faceImageBase64, string $registeredDescriptor): bool
    {
        return true;
    }
}
