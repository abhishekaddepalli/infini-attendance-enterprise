# Infini Attendance - API Documentation v2.0

## Base URL
```
https://app.infiniattendance.com/api/v1
```

## Authentication

All API requests require JWT authentication via Bearer token.

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "admin@company.com",
  "password": "securepass123"
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@company.com",
      "role": "super_admin"
    },
    "token": "eyJhbGciOiJIUzI1NiIs...",
    "refresh_token": "rf_abc123..."
  }
}
```

## API Endpoints

### Attendance

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/attendance/check-in` | Mark attendance check-in |
| POST | `/attendance/check-out` | Mark attendance check-out |
| GET | `/attendance/today-summary` | Today's attendance summary |
| GET | `/attendance/calendar?month=2024-01` | Monthly calendar view |
| GET | `/attendance/live` | Real-time live attendance |
| POST | `/attendance/corrections/request` | Request attendance correction |

### Face Recognition

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/face-recognition/register` | Register employee face |
| POST | `/face-recognition/verify` | Verify face for attendance |
| POST | `/face-recognition/liveness-check` | Liveness detection |
| POST | `/face-recognition/anti-spoof` | Anti-spoofing check |
| GET | `/face-recognition/faces/{employee}` | Get employee faces |
| DELETE | `/face-recognition/faces/{employee}` | Delete employee faces |

### Biometric Devices

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/devices` | List all devices |
| POST | `/devices` | Add new device |
| POST | `/devices/{id}/connect` | Connect to device |
| POST | `/devices/{id}/sync` | Sync attendance logs |
| POST | `/devices/{id}/restart` | Remote restart device |
| GET | `/devices/{id}/health` | Health check |
| POST | `/devices/discover` | Network device discovery |

### Employees

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/employees` | List employees (paginated) |
| POST | `/employees` | Create employee |
| GET | `/employees/{id}` | Get employee details |
| PUT | `/employees/{id}` | Update employee |
| DELETE | `/employees/{id}` | Delete employee |
| POST | `/employees/bulk-import` | Bulk import (CSV/Excel) |

### Leave Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/leave/requests` | Leave requests |
| POST | `/leave/apply` | Apply for leave |
| POST | `/leave/{id}/approve` | Approve leave |
| POST | `/leave/{id}/reject` | Reject leave |
| GET | `/leave/balance/{employee}` | Leave balance |

### Payroll

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/payroll/process` | Process monthly payroll |
| GET | `/payroll/slips/{employee}` | View salary slips |
| GET | `/payroll/slip/{id}/download` | Download salary slip PDF |
| GET | `/payroll/summary` | Payroll summary |

### Payments

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/payment/gateways` | Available payment gateways |
| POST | `/payment/razorpay/create-order` | Create Razorpay order |
| POST | `/payment/razorpay/verify` | Verify Razorpay payment |
| POST | `/payment/stripe/create-intent` | Create Stripe intent |
| POST | `/payment/apply-coupon` | Apply coupon code |
| GET | `/payment/billing-history` | Billing history |
| GET | `/payment/invoices/{id}/download` | Download invoice PDF |

### AI Assistant

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/ai/chat` | Chat with AI assistant |
| POST | `/ai/voice` | Voice command |
| GET | `/ai/attendance-prediction` | Attendance predictions |
| GET | `/ai/executive-summary` | Executive summary |
| POST | `/ai/nlp-query` | Natural language query |

### Reports

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/reports/attendance` | Attendance report |
| GET | `/reports/payroll` | Payroll report |
| POST | `/reports/custom` | Generate custom report |
| GET | `/reports/export/{id}` | Export report (PDF/Excel) |

## Error Codes

| Code | Description |
|------|-------------|
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Invalid/missing token |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 422 | Validation Error - Invalid data |
| 429 | Too Many Requests - Rate limited |
| 500 | Internal Server Error |

## Rate Limiting

- General API: 1000 requests/minute
- Attendance check-in: 10 requests/minute
- Face recognition: 5 requests/minute
- Reports: 50 requests/hour
