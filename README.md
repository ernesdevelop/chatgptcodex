# Modular PHP Insurance App

Simple CRUD app (plain PHP + PDO) for managing clients and insurance policies.

## Project Structure

- `public/` dashboard and CRUD endpoints
- `config/database.php` central PDO connection function
- `includes/` shared layout and helper functions
- `assets/css/styles.css` UI customizations
- `database/schema.sql` MySQL schema

## Run Locally

1. Create a MySQL database (example: `insurance_app`).
2. Import the schema:
   ```bash
   mysql -u root -p insurance_app < database/schema.sql
   ```
3. Configure database credentials in `config/database.php`.
4. Start PHP built-in server from repository root:
   ```bash
   php -S localhost:8000 -t public
   ```
5. Open:
   - http://localhost:8000

## Features

- Dashboard with expiring today, next 7 days, and expired policy sections.
- Clients CRUD with name search (`?q=`).
- Policies CRUD joined with client data.
- WhatsApp quick-action link with pre-filled renewal message.
- Mobile-first Bootstrap 5 layout.
