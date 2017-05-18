<?php
/**
 * Created by PhpStorm.
 * User: tjano
 * Date: 18/05/2017
 * Time: 14:09
 */

namespace PPIL\models;


class NotificationResponsabilite extends Notification
{
    protected $table = "NotificationResponsabilite";
    protected $primaryKey = "id_notification";
    public $timestamps = false;

    public static function appliquer($notification_responsabilite, $notification)
    {
        $r = new Responsabilite();
        $enseignant  = $notification->mail_source;
        $intitule = $notification_responsabilite->intitule;
        $id_formation = $notification_responsabilite->id_formation;
        $id_UE = $notification_responsabilite->id_UE;

        $r->ajoutResponsabilite($enseignant,$intitule,$id_formation,$id_UE);


        $mail = new MailControler();
        $mail->sendMaid($enseignant,'Responsabilite','Votre demande de responsabilité a été validée par le responsable du département informatique.');

        $notification_responsabilite->delete();
        $notification->delete();

    }
}