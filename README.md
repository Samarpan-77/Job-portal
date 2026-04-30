# Seven-7 (AI Job Portal)

Seven-7 is a PHP + MySQL job portal built with a simple MVC structure.

## Features

- User registration and login with session auth + CSRF protection
- Secure password reset flow with:
  - `password_hash()` for stored passwords
  - `password_verify()` during login
  - 64-character random reset tokens (`bin2hex(random_bytes(32))`)
  - Token hashing in DB (`SHA-256`) + 2-minute expiry + single-use invalidation
  - Real SMTP email delivery (PHPMailer)
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
- PHPMailer (SMTP)
- OpenRouter (AI interview practice)

## Project Structure

Canonical source root: the project uses the top-level `app/` directory.

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
4. If your DB already existed before this update, run this SQL once in phpMyAdmin:
   ```sql
   CREATE TABLE IF NOT EXISTS password_resets (
       id INT AUTO_INCREMENT PRIMARY KEY,
       user_id INT NOT NULL,
       token_hash CHAR(64) NOT NULL UNIQUE,
       expires_at DATETIME NOT NULL,
       used_at DATETIME NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       CONSTRAINT fk_password_resets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
       INDEX idx_password_resets_user_id (user_id),
       INDEX idx_password_resets_expires_at (expires_at)
   );
   ```
5. Install PHPMailer from project root:
   ```powershell
   composer require phpmailer/phpmailer
   ```
6. Copy `.env.example` values into `.env` for Mailpit SMTP:
   - `AI_PROVIDER=openrouter`
   - `OPENROUTER_API_KEY=your_openrouter_api_key_here`
   - `OPENROUTER_MODEL=~openai/gpt-mini-latest`
   - `OPENROUTER_HTTP_REFERER=http://localhost/Seven-7/public/`
   - `OPENROUTER_APP_NAME=Seven-7`
   - `MAIL_HOST=127.0.0.1`
   - `MAIL_PORT=1025`
   - `MAIL_USERNAME=`
   - `MAIL_PASSWORD=`
   - `MAIL_ENCRYPTION=none`
   - `MAIL_SMTP_AUTH=0`
   - `MAIL_FROM_ADDRESS=noreply@seven7.local`
   - `MAIL_FROM_NAME=Seven-7`
   - `MAIL_SHOW_DEV_RESET_LINK=0`
7. Ensure DB config in `app/config/config.php` matches your local setup:
   - `DB_HOST=localhost`
   - `DB_NAME=job-portal`
   - `DB_USER=root`
   - `DB_PASS=` (empty by default)
8. Open app:
   - `http://localhost/Seven-7/public/`

## Mailpit (Local SMTP)

- This project uses **Mailpit** for local SMTP testing (not MailHog).
- Start Mailpit and keep it running while testing password reset emails.
- Start it with Docker Compose from the project root:
  ```powershell
  docker compose -f docker-compose.mailpit.yml up -d
  ```
- Or run it directly with Docker:
  ```powershell
  docker run -d --name seven7-mailpit -p 1025:1025 -p 8025:8025 axllent/mailpit:latest
  ```
- Open the Mailpit inbox UI:
  - `http://localhost:8025`
- SMTP connection for this app:
  - Host: `127.0.0.1`
  - Port: `1025`
  - Encryption: `none`
  - Auth: disabled

## OpenRouter (AI Interview)

- The AI interview feature is configured to use OpenRouter.
- Default model:
  - `~openai/gpt-mini-latest`
- Required `.env` values:
  - `AI_PROVIDER=openrouter`
  - `OPENROUTER_API_KEY=your_openrouter_api_key_here`
- Optional `.env` values:
  - `OPENROUTER_MODEL=~openai/gpt-mini-latest`
  - `OPENROUTER_HTTP_REFERER=http://localhost/Seven-7/public/`
  - `OPENROUTER_APP_NAME=Seven-7`

## Reset Password Test

1. Open `http://localhost/Seven-7/public/forgot-password`
2. Submit a registered email address
3. Check inbox/spam for the reset email
4. Open the reset link (expires in 15 minutes)
5. Set a new password and login at `http://localhost/Seven-7/public/login`

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
  - `/public/forgot-password`
  - `/public/reset-password?token=<token>`
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
