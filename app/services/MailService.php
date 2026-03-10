<?php

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    private static function loadMailer(): bool
    {
        $psrLoggerInterfacePath = BASE_PATH . '/Psr/Log/LoggerInterface.php';
        if (!interface_exists('Psr\\Log\\LoggerInterface') && is_file($psrLoggerInterfacePath)) {
            require_once $psrLoggerInterfacePath;
        }

        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return true;
        }

        $autoloadPath = BASE_PATH . '/vendor/autoload.php';
        if (is_file($autoloadPath)) {
            require_once $autoloadPath;
        }

        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $phpMailerBase = BASE_PATH . '/PHPMailer/src';
            $requiredFiles = [
                $phpMailerBase . '/Exception.php',
                $phpMailerBase . '/SMTP.php',
                $phpMailerBase . '/PHPMailer.php',
            ];

            foreach ($requiredFiles as $file) {
                if (is_file($file)) {
                    require_once $file;
                }
            }
        }

        return class_exists('PHPMailer\PHPMailer\PHPMailer');
    }

    public static function sendPasswordResetEmail(
        string $toEmail,
        string $toName,
        string $resetLink,
        int $expiresInSeconds = 900
    ): bool
    {
        if (!self::loadMailer()) {
            error_log('PHPMailer is not installed. Run: composer require phpmailer/phpmailer');
            return false;
        }

        $mailHost = (string)(getenv('MAIL_HOST') ?: '');
        $mailPort = (int)(getenv('MAIL_PORT') ?: 1025);
        $mailUsername = (string)(getenv('MAIL_USERNAME') ?: '');
        $mailPassword = (string)(getenv('MAIL_PASSWORD') ?: '');
        $mailEncryption = strtolower((string)(getenv('MAIL_ENCRYPTION') ?: 'none'));
        $mailSmtpAuth = filter_var((string)(getenv('MAIL_SMTP_AUTH') ?: '0'), FILTER_VALIDATE_BOOLEAN);
        $mailFromAddress = (string)(getenv('MAIL_FROM_ADDRESS') ?: $mailUsername);
        $mailFromName = (string)(getenv('MAIL_FROM_NAME') ?: 'Seven-7');

        if ($mailHost === '' || $mailFromAddress === '') {
            error_log('SMTP is not configured. Please set MAIL_* values in .env');
            return false;
        }
        if ($mailSmtpAuth && ($mailUsername === '' || $mailPassword === '')) {
            error_log('SMTP auth enabled but MAIL_USERNAME/MAIL_PASSWORD are missing.');
            return false;
        }

        $expiresInMinutes = max(1, (int)ceil($expiresInSeconds / 60));
        $expiryText = $expiresInMinutes . ' minute' . ($expiresInMinutes === 1 ? '' : 's');

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $mailHost;
            $mail->SMTPAuth = $mailSmtpAuth;
            if ($mailSmtpAuth) {
                $mail->Username = $mailUsername;
                $mail->Password = $mailPassword;
            }
            $mail->Port = $mailPort;
            if ($mailEncryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($mailEncryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = '';
                $mail->SMTPAutoTLS = false;
            }
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($mailFromAddress, $mailFromName);
            $mail->addAddress($toEmail, $toName !== '' ? $toName : $toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = '<p>You requested a password reset.</p>'
                . '<p>This link expires in ' . htmlspecialchars($expiryText, ENT_QUOTES, 'UTF-8') . ':</p>'
                . '<p><a href="' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '">Reset Password</a></p>'
                . '<p>If you did not request this, you can ignore this email.</p>';
            $mail->AltBody = "You requested a password reset.\n"
                . "This link expires in {$expiryText}:\n"
                . $resetLink . "\n\n"
                . "If you did not request this, ignore this email.";

            return $mail->send();
        } catch (Throwable $e) {
            error_log('Password reset email failed: ' . $e->getMessage());
            return false;
        }
    }
}
