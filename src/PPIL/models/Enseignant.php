<?php
namespace PPIL\models;

class Enseignant extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Enseignant";
	protected $primaryKey = "mail";
	public $timestamps = false;

	public static function inscription($mail, $nom, $prenom, $statut, $mdp) {
		$new_notification_inscription = new NotificationInscription();
		$new_notification_inscription->nom = $nom;
		$new_notification_inscription->prenom = $prenom;
		$new_notification_inscription->statut = $statut;
		$new_notification_inscription->mail = $mail;
		$new_notification_inscription->mot_de_passe = $mdp;
		$resp = Responsabilite::where('intituleResp', '=', 'ResponsableDI')->first();
		$ens_respDI = Enseignant::where('id_responsabilite', '=', $resp->id_responsabilite)->first();
		$new_notification_inscription->destinataire = $ens_respDI->mail;
		$new_notification_inscription->save();
		$new_notification_inscription->notification()->save($new_notification_inscription);
}


	
}