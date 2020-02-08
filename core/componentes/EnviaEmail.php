<?php
/**
 * Created by PhpStorm.
 * User: Hugo Leonardo
 * Date: 14/03/2019
 * Time: 13:31
 */

namespace componentes;

use modelo\PHPMailer;
use fs\Diretorios;
use modelo\Funcionario;
use modelo\PHPMailer\Exception;

class EnviaEmail
{

    private $assunto;
    private $mensagem;
    private $anexo;
    private $deEmail;
    private $deNome;
    private $paraEmail;
    private $paraNome;
    private $mail;

    public function __construct($paramAssunto = null,
                                $paramMensagem = null,
                                $paramDeEmail,
                                $paramDeNome,
                                $paramParaEmail,
                                $paramParaNome,
                                $paramAnexo = null)
    {

        $this->assunto = $paramAssunto;
        $this->mensagem = $paramMensagem;
        $this->deEmail = $paramDeEmail;
        $this->deNome = $paramDeNome;
        $this->paraEmail = $paramParaEmail;
        $this->paraNome = $paramParaNome;
        $this->anexo = $paramAnexo;

        try {

            $this->mail = new PHPMailer(true);
            $this->setConfiguracao();
            $this->setEnderecoEmail();
            if (!empty($paramAnexo)) {
                $this->setAnexoCupom();
            }

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->mail->ErrorInfo;
        }
    }

    private function setConfiguracao()
    {
        $this->mail->SMTPDebug = 0; //2 - verbose |                                                       // Enable verbose debug output
        $this->mail->isSMTP();                                                                            // Set mailer to use SMTP
        $this->mail->Host = 'smtpac.correiosnet.int';                                                     // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = false;                                                                    // Enable SMTP authentication
        $this->mail->Port = 25;
    }

    private function setEnderecoEmail()
    {
        $this->mail->setFrom($this->deEmail, $this->deNome);
        $this->mail->addAddress($this->paraEmail, utf8_decode($this->paraNome));
        $this->mail->addReplyTo($this->deEmail, $this->deNome);
    }

    private function setAnexoCupom()
    {

        $image = Diretorios::get('cupom') . $this->anexo . '.png';
        $data = fopen($image, 'rb');
        $size = filesize($image);
        $contents = fread($data, $size);
        fclose($data);
        $base64img = 'data:image/png;base64,' . base64_encode($contents);
        $is64Go = substr($base64img, strpos($base64img, ","));
        $filename = "cupon-b64-attach-" . $this->anexo . ".png";
        $encoding = "base64";
        $type = "image/png";
        $this->mail->AddStringAttachment(base64_decode($is64Go), $filename, $encoding, $type, 'inline');
        $this->mail->AddStringEmbeddedImage(base64_decode($is64Go), 'cupom', 'cupon-b64-body-' . $this->anexo . '.png', $encoding, $type, 'inline');

    }

    public function setEnviar()
    {
        try {

            $this->mail->isHTML(true);                                                                             // Set email format to HTML
            $this->mail->Subject = $this->assunto;
            if (!empty($this->anexo)) {
                $this->mail->Body = utf8_decode($this->mensagem . '<br><br><img src="cid:cupom" />');
            }else{
                $this->mail->Body = utf8_decode($this->mensagem);
            }
            $this->mail->AltBody = utf8_decode($this->mensagem);
            $this->mail->send();

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->mail->ErrorInfo;
        }
    }


}