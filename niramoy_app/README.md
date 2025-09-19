# Niramoy (Minimal PHP)

This is a minimal PHP project scaffold to explore the `niramoy_health` database you provided.

Requirements
- XAMPP (or PHP + MySQL/MariaDB)

Setup
1. Place the `niramoy_app` folder inside your webroot (already at `c:/xampp/htdocs/file/niramoy_app`).
2. Copy `inc/config.sample.php` to `inc/config.php` and update DB credentials.
3. Import the provided `niramoy_health.sql` into your MySQL server (use phpMyAdmin or `mysql` CLI).
   - Example (PowerShell):
```powershell
mysql -u root -p < c:\xampp\htdocs\file\niramoy_health.sql
```
4. Open `http://localhost/file/niramoy_app/` in your browser.

API
- `api.php?q=hospitals` - GET list of hospitals
- `api.php?q=doctors` - GET doctors
- `api.php?q=users` - GET users
- `api.php?q=appointments` - GET appointments; POST to create an appointment (JSON body)

Notes
- This is intentionally minimal: no authentication, input validation beyond basics, or CSRF protection. Use only for local development and learning.
