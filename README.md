#  Employee Manager API (Anglebert Team)

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](https://opensource.org/licenses/MIT)

A professional, industrial-grade **Employee Management & Attendance System** built with **Laravel 12**. This system provides a robust API for managing employee lifecycles, tracking attendance with real-time notifications, and generating automated daily reports.

---

##  Features

- **Stateless Authentication**: Powered by Laravel Sanctum (Register, Login, Logout).
- **Welcome Emails**: New users receive a welcome notification upon registration.
- **Secure Password Reset**: Modern 6-digit OTP delivery via email for stateless password recovery.
- **Employee CRUD**: Strictly validated endpoints for employee lifecycle management.
- **Attendance Management**: 
  - **Check-in/Check-out**: Seamless timestamping for employees.
  - **Instant Notifications**: Employees receive real-time email confirmations for every check-in and check-out.
- **Automated Reporting**: 
  - **Daily Report Scheduler**: Every night at **11:59 PM**, the system automatically compiles all attendance data for the day.
  - **Automated Delivery**: The report (PDF & Excel) is automatically emailed to all registered users.
- **Background Processing**: All emails and reports are processed via background queues for maximum API performance.
- **API Documentation**: Interactive OpenAPI/Swagger documentation using PHP 8 attributes.

---

##  Tech Stack

- **Kernel**: [Laravel 12](https://laravel.com)
- **Database**: PostgreSQL (Primary) / SQLite (Testing)
- **Auth**: [Laravel Sanctum](https://laravel.com/docs/sanctum)
- **Reports**: [Laravel Snappy](https://github.com/barryvdh/laravel-snappy) & [Laravel Excel](https://laravel-excel.com/)
- **API Docs**: [Swagger-PHP](https://github.com/zircote/swagger-php) (OpenAPI v3)

---

##  Installation & Setup

### 1. Prerequisites
- PHP 8.2+
- Composer
- Database (PostgreSQL or SQLite)
- **wkhtmltopdf** (Required for PDF reports)

### 2. Clone & Install
```bash
git clone https://github.com/Anglebert-Dev/employee-management-system
cd employee-management-system
composer install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```
**Update your `.env` file with:**
- `DB_CONNECTION`, `DB_DATABASE`, etc.
- `MAIL_*` credentials (see Email Setup below).

### 4. Database Setup
```bash
php artisan migrate
```

### 5. Running the App
```bash
# Start the web server
php artisan serve

# Start the queue worker (Required for all notifications and reports)
php artisan queue:work

# Run the scheduler (To process the nightly 11:59 PM report)
php artisan schedule:work

# Manual Trigger: Send today's report immediately
php artisan aam:send-daily-report
```

---

##  Email & Queue Configuration

This application uses background queues to send emails. Ensure your `QUEUE_CONNECTION` is set (e.g., `database` or `redis`).

### Gmail SMTP Setup
1. Enable 2-Step Verification on your Google Account.
2. Generate an **App Password** at [Google App Passwords](https://myaccount.google.com/apppasswords).
3. Update `.env`:
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_FROM_ADDRESS="your-email@gmail.com"
```

---

##  wkhtmltopdf Setup Guide

### Issue Summary
The `barryvdh/laravel-snappy` package requires absolute paths to the wkhtmltopdf binary. Relative paths like `vendor/wemersonjanuario/...` will fail with errors like: `'vendor' is not recognized as an internal or external command`. This is a known limitation of the package's path resolution on Windows.

### Solution
Our `config/snappy.php` uses Laravel's `base_path()` helper to automatically convert relative paths to absolute paths based on your project location.

### Platform-Specific Instructions

#### Windows
- **Run composer install**: Binary is included via `wemersonjanuario/wkhtmltopdf-windows`.
- **No .env configuration needed**: Works automatically.

#### Linux/Mac
- **Install wkhtmltopdf**: `sudo apt-get install wkhtmltopdf` (Ubuntu) or `brew install wkhtmltopdf` (Mac).
- **No .env configuration needed**: Config auto-detects system binary at `/usr/local/bin/wkhtmltopdf`.

### Troubleshooting
**Error: "path not found" or "command not found"**
- **Check Paths**: Run `php artisan tinker` → `config('snappy.pdf.binary')`. Should be an absolute path, not `vendor/...`.
- **Clear Cache**: `php artisan config:clear && php artisan config:cache`.

**Reference**: [barryvdh/laravel-snappy](https://github.com/barryvdh/laravel-snappy)

---

##  Automated Scheduler (AAM)

The system includes a built-in scheduler (Anglebert Attendance Manager - AAM) that handles automated daily operations.

### Nightly Attendance Report
- **Schedule**: Runs every day at **23:59 (11:59 PM)**.
- **Action**: Generates a consolidated attendance report (PDF & Excel) for the entire day.
- **Recipients**: The report is automatically emailed to **all registered users**.

### How to Run the Scheduler
For the automated tasks to run, the Laravel scheduler must be active:
```bash
# Start the scheduler in your terminal
php artisan schedule:work
```

### Manual Command (Testing)
If you want to trigger the report generation immediately without waiting for midnight, use the custom `aam` command:
```bash
# Send today's report now
php artisan aam:send-daily-report

# Send a report for a specific date
php artisan aam:send-daily-report 2026-02-05
```
*Note: This command is handled by `app/Console/Commands/SendDailyAttendanceReport.php`.*

---

##  API Documentation

Access the interactive API documentation:
- **Swagger UI**: `http://127.0.0.1:8000/docs`
- **OpenAPI JSON**: `http://127.0.0.1:8000/api/openapi.json`

### Authentication Flow
1. **Register/Login**: Receive a `access_token`.
2. **Authorize**: Include the token in the header: `Authorization: Bearer <your_token>`.

---

##  Testing

The project is fully tested using PHPUnit.
```bash
php artisan test
```


