# Portfolio — Full-Stack PHP/MySQL

A production-ready personal portfolio with admin dashboard, AJAX-driven content, dark/light mode, and a full contact system.

## Tech Stack
- **Frontend:** HTML5, CSS3 (Glassmorphism), Vanilla JS
- **Backend:** PHP 8, MySQL, PDO
- **Features:** AJAX projects/contact, Admin Dashboard, Dark Mode, Responsive

## Quick Start

### 1. Requirements
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10+
- Apache/Nginx with mod_rewrite (or XAMPP/Laragon locally)

### 2. Clone / Download
```bash
git clone https://github.com/yourname/portfolio.git
cd portfolio
```

### 3. Database Setup
1. Open phpMyAdmin (or MySQL CLI)
2. Create a new database: `portfolio_db`
3. Import `database.sql`
```bash
mysql -u root -p portfolio_db < database.sql
```

### 4. Configuration
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'your_mysql_username');
define('DB_PASS', 'your_mysql_password');
define('SITE_NAME', 'Your Name');
define('SITE_EMAIL', 'you@email.com');
define('SITE_URL',  'http://localhost/portfolio');
```

### 5. Personalise
- Replace **`YN.`** logo with your initials everywhere
- Add your profile photo at `assets/images/profile.jpg`
- Update social links in `index.php` and `includes/footer.php`
- Update stats, bio text, and CV link in `index.php`

### 6. Admin Login
- URL: `http://localhost/portfolio/login.php`
- Username: `admin`  |  Password: `Admin@123`
- **Change the password after first login!**

## Folder Structure
```
portfolio/
├── admin/                  # Admin dashboard
│   ├── assets/admin.js
│   ├── includes/
│   ├── index.php           # Dashboard
│   ├── projects.php        # Project CRUD
│   └── messages.php        # Contact messages
├── api/                    # AJAX endpoints
│   ├── projects.php
│   ├── contact.php
│   ├── admin_projects.php
│   └── admin_messages.php
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/
├── config/database.php     # DB config & constants
├── includes/               # Shared PHP partials
│   ├── header.php
│   ├── footer.php
│   └── functions.php       # All DB functions
├── uploads/                # Uploaded project images
├── index.php               # Main portfolio page
├── login.php
├── logout.php
└── database.sql            # Full DB schema + seed data
```

## Hosting (InfinityFree / 000WebHost)
1. Zip the `portfolio/` folder and upload via File Manager
2. Create MySQL database in control panel
3. Import `database.sql`
4. Update `config/database.php` with hosting credentials
5. Update `SITE_URL` to your domain

## Security Notes
- Passwords are hashed with `password_hash()` (bcrypt)
- All DB queries use PDO prepared statements
- Sessions are httponly-cookie protected
- All user input is sanitised before display
