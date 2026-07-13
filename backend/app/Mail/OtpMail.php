<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mã xác thực OTP đặt lại mật khẩu - Ecom Fashion',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee;'>
                    <h2 style='color: #333; text-align: center; font-weight: bold;'>ECOM FASHION</h2>
                    <p>Xin chào,</p>
                    <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng sử dụng mã OTP dưới đây để tiếp tục:</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <span style='font-size: 24px; font-weight: bold; letter-spacing: 5px; padding: 10px 20px; background-color: #f5f5f5; border: 1px solid #ddd; border-radius: 4px;'>{$this->otp}</span>
                    </div>
                    <p style='color: #666; font-size: 13px;'>Mã xác thực này có hiệu lực trong vòng 10 phút. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                    <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                    <p style='color: #999; font-size: 11px; text-align: center;'>Đây là email tự động, vui lòng không trả lời.</p>
                </div>
            "
        );
    }
}
