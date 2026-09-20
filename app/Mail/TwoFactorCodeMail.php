<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: 'Votre code de vérification NutriTrace',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    protected function logoDataUri(): string
    {
        $path = public_path('images/nutritrace-logo.png');

        if (! file_exists($path)) {
            return '';
        }

        $data = base64_encode(file_get_contents($path));

        return "data:image/png;base64,{$data}";
    }

    protected function buildHtml(): string
    {
        $code = e($this->code);
        $logo = $this->logoDataUri();

        $logoImg = $logo
            ? "<img src=\"{$logo}\" alt=\"NutriTrace\" width=\"64\" height=\"64\" style=\"display:block; margin:0 auto 12px;\" />"
            : '';

        return <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head><meta charset="UTF-8" /></head>
        <body style="margin:0; padding:0; background:#f4f9f8; font-family: Arial, sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
                <tr>
                    <td align="center">
                        <table width="420" cellpadding="0" cellspacing="0" style="background:#ffffff; border:1px solid #dce9ed;">
                            <tr>
                                <td style="background:#05342d; padding:32px 24px; text-align:center;">
                                    {$logoImg}
                                    <div style="color:#ffffff; font-size:22px; font-weight:700; letter-spacing:-0.5px; margin-bottom:6px;">
                                        Nutri<span style="color:#8fd39a;">Trace</span>
                                    </div>
                                    <div style="color:#8fd39a; font-size:11px; font-weight:700; letter-spacing:2px;">
                                        TRAÇABILITÉ ALIMENTAIRE
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:32px; text-align:center;">
                                    <p style="color:#0b4145; font-size:15px; margin:0 0 20px;">
                                        Voici votre code de vérification :
                                    </p>
                                    <div style="font-size:32px; font-weight:700; letter-spacing:8px; color:#05342d; margin-bottom:20px;">
                                        {$code}
                                    </div>
                                    <p style="color:#789096; font-size:13px; margin:0;">
                                        Ce code expire dans 10 minutes. Si vous n'avez pas demandé ce code, ignorez cet e-mail.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        HTML;
    }
}