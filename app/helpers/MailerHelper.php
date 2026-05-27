<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerHelper
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com'; // Change to your SMTP host
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'kyyrizvz@gmail.com'; // SMTP username
            $this->mail->Password   = 'wiik hsin aznf wmbh';    // SMTP password (use App Password for Gmail)
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 587;

            // Default Sender
            $this->mail->setFrom('no-reply@eventin.test', 'Eventin Admin');
        } catch (Exception $e) {
            // Handle error during setup if any
        }
    }

    /**
     * Send OTP to a specific email address
     *
     * @param string $recipientEmail
     * @param string $otpCode
     * @return bool
     */
    public function sendOtp($recipientEmail, $otpCode)
    {
        try {
            $this->mail->addAddress($recipientEmail);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Your OTP Code for Eventin';
            $this->mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                    <h2 style='color: #4F46E5; text-align: center;'>Eventin OTP Verification</h2>
                    <p>Hello,</p>
                    <p>Your One-Time Password (OTP) for verification is:</p>
                    <div style='text-align: center; margin: 20px 0;'>
                        <span style='font-size: 24px; font-weight: bold; padding: 10px 20px; background-color: #F3F4F6; border-radius: 5px; letter-spacing: 5px; color: #111827;'>
                            {$otpCode}
                        </span>
                    </div>
                    <p>This code is valid for 5 minutes. Please do not share it with anyone.</p>
                    <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                    <p style='font-size: 12px; color: #888; text-align: center;'>If you did not request this, please ignore this email.</p>
                </div>
            ";
            $this->mail->AltBody = "Your OTP code is: {$otpCode}. Please do not share it with anyone.";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}
