<?php
namespace PPIL\models;
use PPIL\controlers\MailControler;


class NotificationInscription extends Notification{
	protected $table = "NotificationInscription";
	protected $primaryKey = "id_notification";
    public $incrementing = false;
	public $timestamps = false;

    public static function appliquer($notificationinscription, $notification){
        $e = new Enseignant();
        $e->nom = $notificationinscription->nom;
        $e->prenom = $notificationinscription->prenom;
        $e->mail = $notificationinscription->mail;
        $e->mdp = $notificationinscription->mot_de_passe;
        $e->statut = $notificationinscription->statut;
        switch ($notificationinscription->statut){
        case "Professeur des universités" :
            $e->volumeMin = 192;
            $e->volumeMax = 384;
            break;
        case "Maître de conférences" :
            $e->volumeMin = 192;
            $e->volumeMax = 384;
            break;
        case "PRAG" :
            $e->volumeMin = 384;
            $e->volumeMax = 768;
            break;
        case "ATER" :
            $e->volumeMin = 192;
            $e->volumeMax = 192;
            break;
        case "1/2 ATER" :
            $e->volumeMin = 96;
            $e->volumeMax = 96;
            break;
        case "Doctorant" :
            $e->volumeMin = 64;
            $e->volumeMax = 64;
            break;
        case "Vacataire" :
            $e->volumeMin = 0;
            $e->volumeMax = 96;
            break;
        }
        $nom_source = $notificationinscription->nom;
        $prenom_source = $notificationinscription->prenom;
        $tmp = rand(0,9);
        for($i = 0; $i < 8 ; $i++){
            $tmp = $tmp . rand(0,9);
        }
        $e->rand = $tmp;
        $e->save();
        $mail = new MailControler();
        $mail->sendMail($e->mail,'Inscription Service Enseignant','Votre inscription au sein de l\'application de Service Enseignant a été validée par le responsable du département informatique.');

        $notificationinscription->delete();
        $notification->delete();
    }

}

/*
  exemple d'utilisation :
  $n = new Notification();
  $n->message = "Inscription";
  $n->besoin_validation = 1;
  $n->validation = 0;
  $n->type_notification = 'PPIL\models\NotificationInscription';

  $resp = Responsabilite::where('intituleResp', '=', 'Responsable du departement informatique')->first();
  $ens_respDI = Enseignant::where('id_responsabilite', '=', $resp->id_resp)->first();
  $n->mail_destinataire = $ens_respDI->mail;
  $n->save();

  $new_notification_inscription = new NotificationInscription();
  $new_notification_inscription->id_notification = $n->id_notification;
  $new_notification_inscription->nom = $nom;
  $new_notification_inscription->prenom = $prenom;
  $new_notification_inscription->statut = $statut;
  $new_notification_inscription->mail = $mail;
  $new_notification_inscription->mot_de_passe = $mdp;
  $new_notification_inscription->save();
*/