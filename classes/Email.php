<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu cuenta';


        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p> <strong> Hola, " . $this->nombre . ". </strong> Creaste tu cuenta en nuestra aplicación! Puedes confirmarla presionando en el siguiente enlace: </p>";
        $contenido .= "<p> Presiona aqui: <a href='". $_ENV['APP_URL'] ."/confirmar?token=" . $this->token  . "'>Confirmar mi cuenta</a> </p>";
        $contenido .= "<p> Si no solicitaste este correo, puedes ignorar el mensaje. </p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        $mail->AltBody = 'Texto alternativo';

        //enviar
        $mail->send();
    }

    public function recuperarPassword() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Recupera tu cuenta';


        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p> <strong> Hola, " . $this->nombre . ". </strong> Solicistaste recuperar tu constraseña, puedes hacerlo presionando en el siguiente enlace: </p>";
        $contenido .= "<p> Presiona aqui: <a href='"  . $_ENV['APP_URL'] .  "/reestablecer?token=" . $this->token  . "'>Recuperar mi cuenta</a> </p>";
        $contenido .= "<p> Si no solicitaste este correo, puedes ignorar el mensaje. </p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        $mail->AltBody = 'Texto alternativo';

        //enviar
        $mail->send();
    }

}