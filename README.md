# HR Management System
A secure PHP/MySQL-based system for managing employee leave requests, attendance, and payroll processing.
## Default Admin Access
**Username:** admin
**Password:** admin123
## Quick Setup
1. Import the SQL file:
   ```bash
   mysql -u root -p company_hr < database/hr_system.sql
## Features
- Leave request submission & approval workflow
- Automated payroll calculations
- Attendance tracking
- Performance reviews
- Responsive design (works on desktop/mobile)
## Installation
### Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx web server
### Setup
1. Import database schema:
   ```bash
   mysql -u [username] -p company_hr < database/hr_system_sql.sql
### Database Connection
// includes/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'company_hr');
### Folder Structure
hr_system/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── includes/
│   ├── auth.php (authentication for leave pages)
│   ├── config.php (database config)
│   ├── footer.php
│   ├── header.php
│   └── navigation.php
├── classes/
│   ├── Employee.php
│   └── LeaveRequest.php
├── database/
│   ├── hr_project_sql.sql
└── pages/
    ├── dashboard.php (would show leave stats/notifications)
    ├── employees.php
    ├── attendance.php
    ├── payroll.php
    ├── performance.php
    ├── login.php
    └── logout.php
### Security Notes
Change default admin credentials immediately after setup
The system uses prepared statements to prevent SQL injection
Passwords are hashed using PHP's password_hash()