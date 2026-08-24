# RAO HBMIS

RAO HBMIS is a PHP and MariaDB hostel booking and management information system. It manages students, rooms, bookings, allocations, payments, users, and reports.

## Requirements

- XAMPP for Windows
- Apache
- MySQL/MariaDB
- PHP with PDO MySQL and cURL enabled
- Optional: an OpenAI API key for the AI features

## Installation

1. Copy the project into `C:\xampp\htdocs\RAO_HBMIS`.
2. Open the XAMPP Control Panel.
3. Start **Apache** and **MySQL**.
4. Open phpMyAdmin at `http://localhost/phpmyadmin`.
5. Select **Import** and choose `database/schema.sql`.
6. Click **Go** to import the database.
7. Open `http://localhost/RAO_HBMIS/auth/login.php`.

The application connects using the settings in `config/database.php`:

- Database: `rao_hbmis`
- Host: `localhost`
- User: `root`
- Password: empty by default in XAMPP

If your MySQL password is different, update `config/database.php`.

## Initial Login

The schema creates one administrator account:

- Email: `admin@rao-hbmis.local`
- Password: `Admin@123`

Change this password after the first login by editing the administrator account in **Users**.

## OpenAI Configuration

The dashboard includes:

- **AI Hostel Assistant**: answers questions using current hostel summary data.
- **Room Recommendations**: suggests rooms for confirmed bookings awaiting allocation.

The AI only provides information and recommendations. It does not automatically create bookings or allocations.

Set the API key in PowerShell:

```powershell
setx OPENAI_API_KEY "your-openai-api-key"
```

Optionally select a model:

```powershell
setx OPENAI_MODEL "gpt-4o-mini"
```

After running `setx`, completely close and reopen the XAMPP Control Panel, then restart Apache. Do not place the API key in JavaScript, HTML, or a committed project file.

The PHP cURL extension must be enabled. In `C:\xampp\php\php.ini`, make sure this line is enabled:

```ini
extension=curl
```

Restart Apache after changing `php.ini`.

## Troubleshooting

### Database connection failed

If the application reports that it cannot connect to MySQL:

1. Confirm MySQL is green and running in XAMPP.
2. Confirm the database `rao_hbmis` exists in phpMyAdmin.
3. Re-import `database/schema.sql` if the tables are missing.
4. Check the host, username, database name, and password in `config/database.php`.

### `ERROR 2002 (HY000)` or connection refused

The MariaDB server is stopped or is using a different port. Start MySQL in XAMPP. If it uses another port, update the PDO connection in `config/database.php`, for example:

```php
mysql:host=localhost;port=3307;dbname=rao_hbmis;charset=utf8mb4
```

### AI assistant unavailable

Confirm that `OPENAI_API_KEY` is available to Apache, that cURL is enabled, and that the server has internet access. The AI endpoints return an error without changing database records.

## Main Paths

- `dashboard.php` - authenticated dashboard and AI controls
- `auth/` - login, logout, and role checks
- `admin/` - management pages
- `api/assistant.php` - AI hostel assistant endpoint
- `api/room_recommendations.php` - AI room recommendation endpoint
- `config/database.php` - database connection
- `config/openai.php` - OpenAI API client
- `database/schema.sql` - database schema and initial administrator

## Security Notes

- Keep API keys out of source control.
- Change the initial administrator password immediately.
- Use a non-root MySQL account in production.
- Use HTTPS when deploying outside localhost.
- The schema import drops existing application tables before recreating them; back up production data before importing it.
