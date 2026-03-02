# Seven-7 (AI Job Portal)

Seven-7 is a PHP + MySQL job portal built with a simple MVC structure.

## Features

- User registration and login with session auth + CSRF protection
- Role-based dashboards (`user`, `employer`, `admin`)
- Job posting and job applications
- Resume builder with standardized sections:
  - Contact info, summary, skills, education, experience, projects, certifications
- Employer can view applicant resumes
- Resume print/export via browser `Download PDF` button

## Tech Stack

- PHP (XAMPP)
- MySQL (XAMPP)
- Bootstrap 5

## Project Structure

```text
app/
  config/
  controllers/
  middleware/
  models/
  services/
  views/
database/
  schema.sql
public/
  index.php
  test.php
```

## Setup (Local - XAMPP)

1. Put project in:
   - `C:\xampp\htdocs\Seven-7`
2. Start Apache and MySQL from XAMPP Control Panel.
3. Import database schema:
   - Open `http://localhost/phpmyadmin`
   - Create/import using `database/schema.sql`
4. Ensure DB config in `app/config/config.php` matches your local setup:
   - `DB_HOST=localhost`
   - `DB_NAME=job-portal`
   - `DB_USER=root`
   - `DB_PASS=` (empty by default)
5. Open app:
   - `http://localhost/Seven-7/public/`

## Quick Health Checks

- Database test endpoint:
  - `http://localhost/Seven-7/public/test.php`
  - Expected output: `Database Connected Successfully!`

- PHP lint example:
  ```powershell
  C:\xampp\php\php.exe -l app\controllers\AuthController.php
  ```

## Main Routes

- Auth:
  - `/public/login`
  - `/public/register`
  - `/public/logout`
- Dashboards:
  - `/public/dashboard`
- Jobs:
  - `/public/job`
  - `/public/job/create`
- Resumes:
  - `/public/resume`
  - `/public/resume/create`
- Applications:
  - `/public/application/myApplications`
  - `/public/application/employerApplications`

## Notes

- Resume data is stored as JSON in `resumes.content_json`.
- Legacy/plaintext login passwords are auto-upgraded to bcrypt on successful login.

