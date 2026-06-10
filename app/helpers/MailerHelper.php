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
            $this->mail->Host       = $_ENV['SMTP_HOST']; // Change to your SMTP host
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $_ENV['SMTP_USERNAME']; // SMTP username
            $this->mail->Password   = $_ENV['SMTP_PASSWORD']; // SMTP password (use App Password for Gmail)
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = $_ENV['SMTP_PORT'];

            // Default Sender
            $this->mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
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

    /**
     * Send E-Ticket to a specific email address
     *
     * @param string $recipientEmail
     * @param string $namaPeserta
     * @param string $judulEvent
     * @param string $kodeTiket
     * @param string $ticketUrl
     * @return bool
     */
    public function sendTicket($recipientEmail, $namaPeserta, $judulEvent, $kodeTiket, $ticketUrl)
    {
        try {
            $this->mail->clearAddresses(); // Clear previous addresses if object is reused
            $this->mail->addAddress($recipientEmail);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Tiket Berhasil Dipesan: ' . $judulEvent;
            
            $buttonStyle = "display: inline-block; padding: 12px 24px; background-color: #4F46E5; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 15px;";
            
            $this->mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 16px;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <span style='color: #4F46E5; font-size: 14px; font-weight: 900; letter-spacing: 1px;'>EVENTIN TICKET</span>
                    </div>
                    <h2 style='color: #111827; text-align: center; margin-top: 0;'>Pendaftaran Berhasil!</h2>
                    <p style='color: #4b5563;'>Halo <strong>{$namaPeserta}</strong>,</p>
                    <p style='color: #4b5563;'>Terima kasih telah mendaftar untuk event <strong>{$judulEvent}</strong>. Berikut adalah informasi tiket Anda:</p>
                    
                    <div style='background-color: #f8fafc; padding: 20px; border-radius: 12px; border: 1px dashed #cbd5e1; text-align: center; margin: 20px 0;'>
                        <p style='margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: bold;'>Kode Tiket</p>
                        <p style='margin: 5px 0 0 0; color: #0f172a; font-size: 24px; font-weight: 900; font-family: monospace; letter-spacing: 2px;'>{$kodeTiket}</p>
                    </div>

                    <div style='text-align: center;'>
                        <p style='color: #4b5563; margin-bottom: 5px;'>Silakan klik tombol di bawah ini untuk melihat dan mengunduh tiket QR Anda:</p>
                        <a href='{$ticketUrl}' style='{$buttonStyle}'>Lihat Tiket QR Saya</a>
                    </div>
                    
                    <hr style='border: 0; border-top: 1px solid #e5e7eb; margin: 30px 0 20px 0;'>
                    <p style='font-size: 12px; color: #94a3b8; text-align: center;'>Harap simpan tiket ini dan tunjukkan kepada panitia saat acara berlangsung.</p>
                </div>
            ";
            
            $this->mail->AltBody = "Halo {$namaPeserta}, Pendaftaran event {$judulEvent} berhasil! Kode tiket Anda: {$kodeTiket}. Lihat tiket QR Anda di: {$ticketUrl}";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Ticket could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}
