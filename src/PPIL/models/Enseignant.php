<?php
namespace PPIL\models;

class Enseignant extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Enseignant";
	protected $primaryKey = "mail";
    public $incrementing = false;
	public $timestamps = false;

	public static function inscription($mail, $nom, $prenom, $statut, $mdp) {

        $n = new Notification();
        $n->message = "Demande d'inscription";
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
    }
	
	public static function getInfosEnseignant($mail) {
		$utilisateur = Enseignant::where('mail', = , $mail) -> get();
		return $utilisateur;		
	}

	public static function reinitialiserMDP($utilisateur, $nveauMDP_hash){
		$utilisateur->mdp = $nveauMDP_hash;
		$utilisateur->save();
	}


}