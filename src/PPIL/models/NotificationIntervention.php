<?php
namespace PPIL\models;

class NotificationIntervention extends Notification{
	protected $table = "NotificationIntervention";
	protected $primaryKey = "id_notification";
	public $timestamps = false;
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