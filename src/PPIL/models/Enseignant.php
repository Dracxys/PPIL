<?php
namespace PPIL\models;

use Illuminate\Database\Capsule\Manager as DB;

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
        $n->mail_destinataire = Enseignant::get_responsableDI()->mail;
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

	public static function modifie_intervention($enseignant, $id_intervention, $id_UE, $datas, $supprimer) {
        $n = new Notification();
        if($id_intervention == null){
            $n->message = "Ajout intervention";
        } else {
            $n->message = "Modification intervention";
        }
        $n->besoin_validation = 1;
        $n->validation = 0;
        $n->type_notification = 'PPIL\models\NotificationIntervention';
        $resp = Responsabilite::where('intituleResp', '=', 'Responsable UE')
              ->where('id_UE', '=', $id_UE)
              ->first();
        $n->mail_destinataire = Enseignant::where('mail', '=', $resp->enseignant)
                              ->first()
                              ->mail;

        $n->mail_source = $enseignant->mail;
        $n->save();

        $new_notification_intervention = new NotificationIntervention();
		$new_notification_intervention->id_notification = $n->id_notification;

        $new_notification_intervention->heuresCM = $datas['heuresCM'];
        $new_notification_intervention->heuresTD = $datas['heuresTD'];
        $new_notification_intervention->heuresTP = $datas['heuresTP'];
        $new_notification_intervention->heuresEI = $datas['heuresEI'];
        $new_notification_intervention->groupeTD = $datas['heuresTD'];
        $new_notification_intervention->groupeTP = $datas['heuresTP'];
        $new_notification_intervention->groupeEI = $datas['heuresEI'];

		$new_notification_intervention->id_intervention = $id_intervention;
		$new_notification_intervention->id_UE = $id_UE;
		$new_notification_intervention->supprimer = $supprimer;
        $new_notification_intervention->save();
    }

	public static function reinitialiserMDP($utilisateur, $nveauMDP_hash){
		$utilisateur->mdp = $nveauMDP_hash;
		$utilisateur->save();
	}

    public static function get_privilege($utilisateur){
        return $max = DB::table('Responsabilite')
                      ->where('enseignant', '=', $utilisateur->mail)
                      ->max('privilege');
    }

    public static function get_responsableDI(){
        $max = DB::table('Responsabilite')
             ->max('privilege');
        $mail_responsable = Responsabilite::where("privilege", "=", $max)
                          ->first()
                          ->enseignant;
        $responsable = Enseignant::where('mail', '=', $mail_responsable)
                     ->first();
        return $responsable;
    }


    public static function conversionHeuresTD($user){
        $intervention = Intervention::where('mail_enseignant', 'like', $user->mail)->get();
        $heuresTD = 0;
        $heuresCM = 0;
        $heuresTP = 0;
        $heuresEI = 0;
        $heuresTotales = 0;
        foreach ($intervention as $value){
            $heuresTD += $value->heuresTD;
            $heuresCM += $value->heuresCM;
            $heuresEI += $value->heuresEI;
            $heuresTP += $value->heuresTP;
        }
        if($user->statut == "Professeur des universités" || $user->statut == "Maître de conférences"){
            $heuresTotales = $heuresTD + ($heuresCM *(3/2)) + ($heuresEI)* (7/6) + ($heuresTP);
        }else{
            $heuresTotales = $heuresTD + ($heuresCM *(3/2)) + ($heuresEI* (7/6)) + ($heuresTP * (3/2));
        }
        $user->volumeCourant = ceil($heuresTotales);
        $user->save();
    }

    public static function getPourcentageVolumeHoraire($user){
        $pourcentage=0;
        if(!is_null($user->volumeCourant) || $user->volumeCourant != 0){
            $pourcentage = ($user->volumeCourant / $user->volumeMin)*100;
        }
        return $pourcentage;
    }

}