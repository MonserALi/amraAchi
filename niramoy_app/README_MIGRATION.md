Migration instructions

1) Open your database (phpMyAdmin or use `mysql` CLI) and select the `niramoy_health` database.

2) Run the SQL file located at `migrations/001_create_departments_and_ratings.sql` to create the `departments`, `doctor_departments`, and `doctor_ratings` tables and seed basic departments.

Example CLI command (Windows PowerShell):

```powershell
mysql -u root -p niramoy_health < "c:/xampp/htdocs/file/niramoy_app/migrations/001_create_departments_and_ratings.sql"
```

3) Verify the tables were created and that `departments` contains seed rows.

4) API endpoints added/changed:
- `GET /niramoy_app/api.php?q=doctors&page=1&per_page=10` — returns doctors with `avg_rating` and `departments` (comma-separated names)
- `POST /niramoy_app/api.php?q=doctors/rate` — submit ratings. JSON body: `{ "doctor_id": 1, "rating": 5, "comment": "Good" }`
- `GET /niramoy_app/api.php?q=departments&page=1&per_page=10` — returns departments derived from doctor specializations (existing) — after migration you can replace logic to read from `departments` table.

5) After running the migration, you can use the UI pages:
- `doctors.php` — paginated doctor listing with ratings and department info
- `departments.php` — paginated departments; click a department to view doctors filtered by that specialization

If you want, I can also add a small admin page to bulk-assign doctors to departments.
