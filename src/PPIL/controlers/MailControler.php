<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 15/05/2017
 * Time: 14:45
 */

namespace PPIL\controlers;


class MailControler
{
    private $mail;

    public function __construct()
    {
        $this->mail = new \PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Username = 'ppil.email1@gmail.com';
        $this->mail->Password = 'L3INFORMATIQUE';
        $this->mail->Host = "smtp.gmail.com";
        $this->mail->Port = 465;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->SMTPAuth = true;
    }

    public function sendMaid($destinataire, $sujet, $corps){
        $this->mail->CharSet = 'utf-8';
        $this->mail->setFrom('ppil.email1@gmail.com');
        $this->mail->Subject = $sujet;
        $this->mail->addAddress($destinataire);
        $this->mail->Body = $corps;
        $this->mail->Send();
    }

}