<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\MailHelper;

class MailController extends Controller
{
    private MailHelper $mailHelper;

    public function __construct()
    {
        $this->mailHelper = new MailHelper();
    }

    public function enviarComunicado(string $emailDestino, string $nomeDestino, string $mensagemTexto): bool
    {
        $nomeLimpo = htmlspecialchars(html_entity_decode($nomeDestino), ENT_QUOTES, "UTF-8");
        $mensagemFormatada = nl2br(htmlspecialchars(html_entity_decode($mensagemTexto), ENT_QUOTES, "UTF-8"));
        $assunto = "[RoboDrive] Comunicado da administração";

        $corpoHtml = "
            <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 24px; border: 2px solid #07556A;'>
                <h2 style='color: #13F3F7; font-size: 20px; margin-top: 0;'>RoboDrive - Comunicado</h2>
                <p>Olá, <strong>{$nomeLimpo}</strong>,</p>
                <div style='background-color: #000505; border-left: 4px solid #13F3F7; padding: 14px; margin: 18px 0; color: #F2FEFE; line-height: 1.6;'>
                    {$mensagemFormatada}
                </div>
                <hr style='border: 0; border-top: 1px solid #07556A; margin: 20px 0;'>
                <p style='font-size: 12px; color: #91B5BD; margin-bottom: 4px;'>Atenciosamente,<br><strong style='color: #F2FEFE;'>Administração RoboDrive</strong></p>
                <p style='font-size: 11px; color: #4E6B72;'>Este é um comunicado institucional da plataforma RoboDrive. Em caso de dúvidas, responda a este e-mail.</p>
            </div>
        ";

        return $this->mailHelper->enviarEmail($emailDestino, null, "", $assunto, $corpoHtml);
    }

    public function enviarRecuperacaoSenha(string $emailDestino, string $nomeDestino, string $linkRedefinicao): bool
    {
        $nomeLimpo = htmlspecialchars(html_entity_decode($nomeDestino), ENT_QUOTES, "UTF-8");
        $assunto = "[RoboDrive] Recuperação de Senha";

        $corpoHtml = "
            <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                <h2 style='color: #13F3F7;'>RoboDrive - Recuperação de Senha</h2>
                <p>Olá, <strong>{$nomeLimpo}</strong>,</p>
                <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
                <p>Clique no botão abaixo para cadastrar uma nova senha (link válido por 1 hora):</p>
                <p style='margin: 24px 0;'>
                    <a href='{$linkRedefinicao}' style='background-color: #13F3F7; color: #000505; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 4px; text-transform: uppercase;'>Redefinir Minha Senha</a>
                </p>
                <p style='font-size: 12px; color: #91B5BD;'>Se você não solicitou a alteração, ignore este e-mail.</p>
            </div>
        ";

        return $this->mailHelper->enviarEmail($emailDestino, null, "", $assunto, $corpoHtml);
    }

    public function enviarDesativacaoProjeto(string $emailDestino, string $nomeDestino, string $nomeProjeto, string $justificativa, string $autoridade = "administração"): bool
    {
        $nomeLimpo = htmlspecialchars(html_entity_decode($nomeDestino), ENT_QUOTES, "UTF-8");
        $projetoLimpo = htmlspecialchars(html_entity_decode($nomeProjeto), ENT_QUOTES, "UTF-8");
        $motivoHtml = !empty($justificativa) ? nl2br(htmlspecialchars(html_entity_decode($justificativa), ENT_QUOTES, "UTF-8")) : "Sem justificativa detalhada informada.";
        $assinatura = $autoridade === "administração" ? "Administração RoboDrive" : "Coordenação da equipe · RoboDrive";
        $assunto = "[RoboDrive] Projeto Desativado";

        $corpoHtml = "
            <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                <h2 style='color: #F2A058;'>RoboDrive - Projeto Desativado</h2>
                <p>Olá, <strong>{$nomeLimpo}</strong>,</p>
                <p>Informamos que o projeto <strong>\"{$projetoLimpo}\"</strong> foi desativado pela {$autoridade} pelo seguinte motivo:</p>
                <blockquote style='background-color: #000505; border-left: 4px solid #F2A058; padding: 12px; margin: 15px 0; color: #F2FEFE;'>
                    {$motivoHtml}
                </blockquote>
                <p>Caso discorde desta decisão, responda a este e-mail para solicitar revisão.</p>
                <hr style='border: 0; border-top: 1px solid #07556A; margin: 20px 0;'>
                <p style='font-size: 12px; color: #91B5BD;'>Atenciosamente,<br><strong>{$assinatura}</strong></p>
            </div>
        ";

        return $this->mailHelper->enviarEmail($emailDestino, null, "", $assunto, $corpoHtml);
    }

    public function enviarComponenteDesativado(string $emailDestino, string $nomeDestino, string $nomeComponente, array $nomesProjetos): bool
    {
        $nomeLimpo = htmlspecialchars(html_entity_decode($nomeDestino), ENT_QUOTES, "UTF-8");
        $componenteLimpo = htmlspecialchars(html_entity_decode($nomeComponente), ENT_QUOTES, "UTF-8");
        $projetosHtml = implode("", array_map(
            fn($nome) => "<li>" . htmlspecialchars(html_entity_decode($nome), ENT_QUOTES, "UTF-8") . "</li>",
            $nomesProjetos
        ));
        $assunto = "[RoboDrive] Componente desativado";

        $corpoHtml = "
            <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                <h2 style='color: #F2A058;'>RoboDrive - Componente desativado</h2>
                <p>Olá, <strong>{$nomeLimpo}</strong>,</p>
                <p>O componente <strong>\"{$componenteLimpo}\"</strong> foi desativado e não está mais disponível na plataforma. Ele está associado ao(s) seguinte(s) projeto(s) do qual você participa:</p>
                <ul style='color: #F2FEFE;'>{$projetosHtml}</ul>
                <p>Recomendamos revisar o projeto e, se necessário, substituir o componente por outro.</p>
                <hr style='border: 0; border-top: 1px solid #07556A; margin: 20px 0;'>
                <p style='font-size: 12px; color: #91B5BD;'>Atenciosamente,<br><strong>Equipe RoboDrive</strong></p>
            </div>
        ";

        return $this->mailHelper->enviarEmail($emailDestino, null, "", $assunto, $corpoHtml);
    }

    public function enviarDesativacaoConta(string $emailDestino, string $nomeDestino, string $justificativa): bool
    {
        $nomeLimpo = htmlspecialchars(html_entity_decode($nomeDestino), ENT_QUOTES, "UTF-8");
        $motivoHtml = !empty($justificativa) ? nl2br(htmlspecialchars(html_entity_decode($justificativa), ENT_QUOTES, "UTF-8")) : "Sem justificativa detalhada.";
        $assunto = "[RoboDrive] Sua conta foi desativada";

        $corpoHtml = "
            <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                <h2 style='color: #F2A058;'>RoboDrive - Aviso de Desativação de Conta</h2>
                <p>Olá, <strong>{$nomeLimpo}</strong>,</p>
                <p>Informamos que sua conta na plataforma RoboDrive foi desativada pela administração pelo seguinte motivo:</p>
                <blockquote style='background-color: #000505; border-left: 4px solid #F2A058; padding: 12px; margin: 15px 0; color: #F2FEFE;'>
                    {$motivoHtml}
                </blockquote>
                <p>Caso discorde desta decisão ou queira solicitar revisão, responda a este e-mail.</p>
                <hr style='border: 0; border-top: 1px solid #07556A; margin: 20px 0;'>
                <p style='font-size: 12px; color: #91B5BD;'>Atenciosamente,<br><strong>Administração RoboDrive</strong></p>
            </div>
        ";

        return $this->mailHelper->enviarEmail($emailDestino, null, "", $assunto, $corpoHtml);
    }

    public function enviarDesativacaoPostagem(string $emailDestino, string $nomeDestino, string $trechoConteudo, string $justificativa, string $autoridade = "administração"): bool
    {
        $nomeLimpo = htmlspecialchars(html_entity_decode($nomeDestino), ENT_QUOTES, "UTF-8");
        $trechoLimpo = htmlspecialchars(mb_strimwidth($trechoConteudo, 0, 100, "..."), ENT_QUOTES, "UTF-8");
        $motivoHtml = !empty($justificativa) ? nl2br(htmlspecialchars(html_entity_decode($justificativa), ENT_QUOTES, "UTF-8")) : "Sem justificativa detalhada informada.";
        $assinatura = $autoridade === "administração" ? "Administração RoboDrive" : "Coordenação da equipe · RoboDrive";
        $assunto = "[RoboDrive] Sua postagem foi desativada";

        $corpoHtml = "
            <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                <h2 style='color: #F2A058;'>RoboDrive - Postagem Desativada</h2>
                <p>Olá, <strong>{$nomeLimpo}</strong>,</p>
                <p>Informamos que sua postagem no fórum (<em>\"{$trechoLimpo}\"</em>) foi desativada pela {$autoridade} pelo seguinte motivo:</p>
                <blockquote style='background-color: #000505; border-left: 4px solid #F2A058; padding: 12px; margin: 15px 0; color: #F2FEFE;'>
                    {$motivoHtml}
                </blockquote>
                <p>Caso discorde desta decisão, responda a este e-mail para solicitar revisão.</p>
                <hr style='border: 0; border-top: 1px solid #07556A; margin: 20px 0;'>
                <p style='font-size: 12px; color: #91B5BD;'>Atenciosamente,<br><strong>{$assinatura}</strong></p>
            </div>
        ";

        return $this->mailHelper->enviarEmail($emailDestino, null, "", $assunto, $corpoHtml);
    }
}
