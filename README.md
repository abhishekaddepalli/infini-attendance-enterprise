# Infini Attendance - Enterprise Workforce Management Platform

**Smart Attendance. Smarter Workforce.**

Infini Attendance is a complete, production-ready, enterprise-grade, multi-tenant SaaS platform for Workforce Management, HRMS, Campus ERP, and Attendance. Built with Laravel 12 + React + TypeScript + TailwindCSS.

## 🏗️ Architecture

| Layer | Technology |
|-------|-----------|
| Backend API | Laravel 12, PHP 8.3 |
| Frontend | React 19, TypeScript, Vite, TailwindCSS, shadcn/ui |
| Database | MySQL 8.4, Redis 7, MongoDB, Elasticsearch |
| Queue | Redis + Laravel Horizon |
| WebSocket | Laravel WebSockets + Pusher |
| AI | OpenAI GPT-4o, Google Gemini, AWS Rekognition |
| Storage | MinIO / AWS S3 |
| Payments | Razorpay, Stripe, PayPal, PhonePe |
| Monitoring | Prometheus, Grafana |
| Container | Docker + Docker Compose |

## 🚀 Quick Start

### Docker (Recommended)
```bash
cd docker
cp .env.example .env
docker-compose up -d
```

### Manual Installation
```bash
# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Frontend
cd frontend
npm install
npm run build

# Start
php artisan serve
```

### Web Installer
Navigate to `/installer` in your browser for an interactive installation wizard.

## 📦 Features

### Core Attendance (18+ Methods)
- Face Recognition (AI-powered with liveness detection)
- Fingerprint, Palm, Iris
- RFID, NFC, QR Code, Dynamic QR
- GPS, GeoFence, WiFi, IP
- Bluetooth Beacon, Manual, Manager Approval

### Biometric Device Integration (12+ Brands)
- ZKTeco, eSSL, Matrix, Suprema, Hikvision
- Realtime, FingerTec, BioMax, Nitgen, Anviz, Mantra, IDEMIA
- Driver-based architecture for easy extension

### HRMS Modules
- Employee Management, Leave Management, Shift Management
- Payroll (PF, ESI, TDS, Professional Tax)
- Recruitment, Performance, Training
- Assets, Visitors, Expenses

### University ERP
- Students, Faculty, Courses, Subjects
- Timetable, Exams, Results, Hall Tickets
- Fees, Scholarships, Hostel, Library, Transport
- NAAC, AICTE, UGC, NBA Reports

### AI Features
- AI Attendance Assistant, AI HR Assistant
- Attendance Prediction, Attrition Prediction
- Late Prediction, NLP Search, Voice Assistant

### Enterprise Features
- Multi-Tenant (Database Isolation)
- White Label (Custom Domain, Logo, Theme)
- RBAC, 2FA, Passkeys, Audit Logs
- PWA (Offline Support, Push Notifications)
- Payment Gateway Integration
- PDF/Excel/CSV Reports

## 🔐 Security
- AES-256-GCM Encryption
- JWT + Sanctum Authentication
- Rate Limiting, IP Restrictions
- Biometric Data Encryption
- GDPR & DPDP Act 2023 Compliant

## 📱 PWA
Full Progressive Web App support:
- Install to Home Screen
- Offline Mode with Background Sync
- Push Notifications
- App-like Experience on Mobile

## 📄 License
Commercial License - Infini Attendance
