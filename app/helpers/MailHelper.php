<?php

namespace app\helpers;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once(__DIR__."/../libraries/PHPMailer/src/PHPMailer");
require_once(__DIR__."/../libraries/PHPMailer/src/SMTP");
require_once(__DIR__."/../libraries/PHPMailer/src/Exception");




class MailHelper
{
    private string $seuEmail;
    private string $suaSenha;
    private string $seuNome;
    public function enviarEmail($destinatario,$anexo,$formato,$assunto,$mensagem,$altmensage)
    {
        $mail = new PHPMailer(True);
        try
        {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->seuEmail;
            $mail->Password   = $this->suaSenha;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->setFrom($this->seuEmail,$this->seuNome);
            $mail->addAddress($destinatario);
            $mail->addReplyTo($this->seuEmail, 'Informações');
            if($anexo != null)
            {
                $mail->addAttachment($anexo,$formato); 
            }
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $mensagem;
            if($altmensage != null)
            {
                $mail->AltBody = $altmensage;
            }
            $mail->send();
            print "\033[36m Mensagem enviada com sucesso para $destinatario!\033[m";
        }
        catch(Exception $e)
        {
            print "\033[31m Mensagem não pode ser enviada. MAILER ERROR\033[m{$mail->ErrorInfo}";
        }
    }

    /**
     * Get the value of seuEmail
     */
    public function getSeuEmail(): string
    {
        return $this->seuEmail;
    }

    /**
     * Set the value of seuEmail
     */
    public function setSeuEmail(string $seuEmail): self
    {
        $this->seuEmail = $seuEmail;

        return $this;
    }

    /**
     * Get the value of suaSenha
     */
    public function getSuaSenha(): string
    {
        return $this->suaSenha;
    }

    /**
     * Set the value of suaSenha
     */
    public function setSuaSenha(string $suaSenha): self
    {
        $this->suaSenha = $suaSenha;

        return $this;
    }

    /**
     * Get the value of seuNome
     */
    public function getSeuNome(): string
    {
        return $this->seuNome;
    }

    /**
     * Set the value of seuNome
     */
    public function setSeuNome(string $seuNome): self
    {
        $this->seuNome = $seuNome;

        return $this;
    }
}
