<?php

namespace App\Services;

use App\Models\Usuario;
use Resend;

class EnvioEmailService
{
    public function tokenEmail(Usuario $usuario, string $token)
    {
        $usuario->update([
            'email_verification_token' => $token,
        ]);

        $resend = Resend::client(env('RESEND_API_KEY'));

        $resend->emails->send([
            'from' => 'onboarding@resend.dev',
            'to' => $usuario->email,
            'subject' => 'Verifique seu email',
            'html' => "<p>Olá, use o código abaixo para verificar seu email:</p><p style='font-size:28px;font-weight:bold;letter-spacing:4px;'>{$token}</p>",
        ]);
    }
}
