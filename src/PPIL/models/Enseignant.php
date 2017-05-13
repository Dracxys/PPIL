<?php
namespace PPIL\models;

class Enseignant extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Enseignant";
	protected $primaryKey = "mail";
	public $timestamps = false;

	public function inscription($mail, $nom, $prenom, $statut, $mdp) {
	$utilisateur = Enseignant::where('mail', 'like' , $mail) -> get();
	if (empty($utilisateur)){
		$new_notification_inscription = new NotificationInscription();
		$new_notification_inscription->nom = $nom;
		$new_notification_inscription->prenom = $prenom;
		$new_notification_inscription->statut = $statut;
		$new_notification_inscription->mail = $mail;
		$new_notification_inscription->mot_de_passe = $mdp;
		$resp = Responsabilite::where('intituleResp', '=', 'ResponsableDI')->get();
		$ens_respDI = Enseignant::where('id_responsabilite', '=', $resp->id_responsabilite)->get();
		$new_notification_inscription->destinataire = $ens_respDI->mail;
		$new_notification_inscription->save();
		$new_notification_inscription->notification()->save($new_notification_inscription);
		return true;
	}
	return false;
}


	
}