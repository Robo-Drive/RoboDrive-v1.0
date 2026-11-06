<?php

namespace app\helpers;

require_once __DIR__ . '/../libraries/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    private string $seuEmail;
    private string $suaSenha;
    private string $seuNome;

    public function __construct()
    {
        $this->seuEmail = defined('MAIL_USER') ? MAIL_USER : '';
        $this->suaSenha = defined('MAIL_PASS') ? MAIL_PASS : '';
        $this->seuNome  = defined('MAIL_NAME') ? MAIL_NAME : 'RoboDrive';
    }

    public function enviarEmail(string $destinatario,?string $anexo = null, $formato = '', $assunto = '', $mensagem = '', $altmensage = ''): bool
    {
        $mail = new PHPMailer(true);
        try
        {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->seuEmail;
            $mail->Password   = $this->suaSenha;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->seuEmail, $this->seuNome);
            $mail->addAddress($destinatario);
            $mail->addReplyTo($this->seuEmail, $this->seuNome);

            if ($anexo !== null && file_exists($anexo)) {
                $mail->addAttachment($anexo, $formato);
            }

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $mensagem;
            if (!empty($altmensage)) {
                $mail->AltBody = $altmensage;
            }

            return $mail->send();
        }
        catch (Exception $e)
        {
            error_log("Erro ao enviar email para {$destinatario}: " . $mail->ErrorInfo);
            return false;
        }
    }

    public function setSeuEmail(string $seuEmail): self
    {
        $this->seuEmail = $seuEmail;
        return $this;
    }

    public function setSuaSenha(string $suaSenha): self
    {
        $this->suaSenha = $suaSenha;
        return $this;
    }

    public function setSeuNome(string $seuNome): self
    {
        $this->seuNome = $seuNome;
        return $this;
    }
}