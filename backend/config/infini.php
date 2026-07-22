<?php

return [
    'brand' => [
        'name' => env('APP_NAME', 'Infini Attendance'),
        'tagline' => 'Smart Attendance. Smarter Workforce.',
        'primary_color' => '#000080',
        'secondary_color' => '#FFFFFF',
        'accent_color' => '#FF9933',
        'success_color' => '#138808',
    ],

    'tenancy' => [
        'database_isolation' => env('TENANT_DATABASE_ISOLATION', false),
        'subdomain_enabled' => true,
        'custom_domain_enabled' => true,
    ],

    'attendance' => [
        'methods' => [
            'face_recognition', 'fingerprint', 'palm_recognition', 'iris_recognition',
            'rfid', 'nfc', 'qr_code', 'dynamic_qr', 'gps', 'geofence',
            'wifi', 'ip', 'bluetooth_beacon', 'manual', 'manager_approval',
            'selfie', 'voice', 'whatsapp',
        ],
        'grace_period' => 15,
        'auto_checkout_hours' => 16,
    ],

    'face_recognition' => [
        'provider' => env('FACE_RECOGNITION_PROVIDER', 'aws'),
        'confidence_threshold' => 95.0,
        'similarity_threshold' => 97.0,
        'liveness_enabled' => true,
        'anti_spoof_enabled' => true,
    ],

    'biometric' => [
        'drivers' => [
            'zkteco', 'essl', 'matrix', 'suprema', 'hikvision',
            'realtime', 'fingertip', 'biomax', 'nitgen', 'anviz',
            'mantra', 'idemia', 'generic',
        ],
        'sync_interval' => 5,
    ],

    'leave' => [
        'types' => [
            'casual' => ['days' => 12, 'color' => '#3B82F6'],
            'sick' => ['days' => 10, 'color' => '#EF4444'],
            'annual' => ['days' => 30, 'color' => '#10B981'],
            'maternity' => ['days' => 182, 'color' => '#EC4899'],
            'paternity' => ['days' => 15, 'color' => '#8B5CF6'],
            'emergency' => ['days' => 5, 'color' => '#F97316'],
            'lop' => ['days' => 0, 'color' => '#6B7280'],
        ],
        'carry_forward_limit' => 30,
    ],

    'payroll' => [
        'pf_percentage' => 12,
        'esi_threshold' => 21000,
        'esi_percentage' => 0.75,
        'overtime_multiplier' => 1.5,
    ],

    'subscription' => [
        'plans' => [
            'starter' => ['max_employees' => 50, 'monthly' => 2999, 'yearly' => 29990],
            'professional' => ['max_employees' => 500, 'monthly' => 7999, 'yearly' => 79990],
            'enterprise' => ['max_employees' => -1, 'monthly' => 19999, 'yearly' => 199990],
            'university' => ['max_employees' => -1, 'monthly' => 49999, 'yearly' => 499990],
        ],
        'trial_days' => 14,
        'payment_gateways' => ['razorpay', 'stripe', 'paypal', 'phonepe'],
    ],

    'ai' => [
        'provider' => env('AI_PROVIDER', 'openai'),
        'model' => env('AI_MODEL', 'gpt-4o'),
        'features' => [
            'ai_assistant', 'attendance_prediction', 'attrition_prediction',
            'late_prediction', 'nlp_search', 'voice_assistant',
        ],
    ],

    'security' => [
        'encryption' => 'aes-256-gcm',
        '2fa_enabled' => true,
        'audit_log_retention' => 365,
        'rate_limit_api' => 1000,
        'rate_limit_login' => 5,
    ],

    'notification' => [
        'channels' => ['email', 'sms', 'whatsapp', 'push', 'browser'],
        'events' => ['check_in', 'check_out', 'late', 'leave_approved', 'payroll_processed'],
    ],

    'university' => [
        'modules' => [
            'courses', 'students', 'faculty', 'timetable', 'exams',
            'results', 'hostel', 'library', 'transport', 'fees', 'placement',
        ],
        'reports' => ['naac', 'aicte', 'ugc', 'nba'],
    ],

    'localization' => [
        'default_locale' => 'en',
        'supported_locales' => ['en', 'hi', 'ta', 'te', 'mr', 'bn', 'gu', 'kn', 'ml'],
        'timezone' => 'Asia/Kolkata',
        'date_format' => 'd-m-Y',
    ],

    'backup' => [
        'database_daily' => true,
        'files_daily' => true,
        'full_weekly' => true,
        'retention_days' => 30,
    ],

    'monitoring' => [
        'health_checks' => ['database', 'redis', 'queue', 'storage', 'ssl'],
        'alert_email' => env('ALERT_EMAIL'),
    ],
];
