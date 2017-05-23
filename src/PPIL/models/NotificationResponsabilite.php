<?php
/**
 * Created by PhpStorm.
 * User: tjano
 * Date: 18/05/2017
 * Time: 14:09
 */

namespace PPIL\models;
use PPIL\controlers\MailControler;


class NotificationResponsabilite extends Notification
{
    protected $table = "NotificationResponsabilite";
    protected $primaryKey = "id_notification";
    public $timestamps = false;
    public $incrementing = false;

    public static function appliquer($notification_responsabilite, $notification)
    {
        $enseignant  = $notification->mail_source;
        $intitule = $notification_responsabilite->intitule;
        $id_formation = $notification_responsabilite->id_formation;
        $id_UE = $notification_responsabilite->id_UE;

        Responsabilite::ajoutResponsabilite($enseignant,$intitule,$id_formation,$id_UE);


        $mail = new MailControler();
        $mail->sendMail($enseignant,'Responsabilite','Votre demande de responsabilité a été validée par le responsable du département informatique.');

        $notification_responsabilite->delete();
        $notification->delete();

    }
	
	public static function reinitialiserBDD(){
		$req = NotificationResponsabilite::all();
		foreach($req as $i){
			$i->delete();
		}
	}
}