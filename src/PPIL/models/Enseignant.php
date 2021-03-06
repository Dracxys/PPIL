<?php
namespace PPIL\models;

use Illuminate\Database\Capsule\Manager as DB;

class Enseignant extends AbstractModel{
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

	public static function inscriptionParDI($mail, $nom, $prenom, $statut, $mdp){
		$new_enseignant = new Enseignant();
		$new_enseignant->nom = $nom;
		$new_enseignant->prenom = $prenom;
		$new_enseignant->mail = $mail;
		$new_enseignant->statut = $statut;
        switch ($statut){
            case "Professeur des universités" :
                $new_enseignant->volumeMin = 192;
                $new_enseignant->volumeMax = 384;
                break;
            case "Maître de conférences" :
                $new_enseignant->volumeMin = 192;
                $new_enseignant->volumeMax = 384;
                break;
            case "PRAG" :
                $new_enseignant->volumeMin = 384;
                $new_enseignant->volumeMax = 768;
                break;
            case "ATER" :
                $new_enseignant->volumeMin = 192;
                $new_enseignant->volumeMax = 192;
                break;
            case "1/2 ATER" :
                $new_enseignant->volumeMin = 96;
                $new_enseignant->volumeMax = 96;
                break;
            case "Doctorant" :
                $new_enseignant->volumeMin = 64;
                $new_enseignant->volumeMax = 64;
                break;
            case "Vacataire" :
                $new_enseignant->volumeMin = 0;
                $new_enseignant->volumeMax = 96;
                break;
        }
		$new_enseignant->mdp = $mdp;
		$new_enseignant->rand = rand(100000000, 1000000000);
		$new_enseignant->save();
	}

	public static function modifie_intervention($enseignant, $id_intervention, $id_UE, $datas, $supprimer, $nom_UE, $nom_formation, $ajout) {

        if($id_intervention == null && $id_UE == null
        && $nom_UE != null && $nom_formation != null){
            # ajout d'une intervention hors fst
            # Pas besoin de validation, on crée et applique la notification
            $n = new Notification();
            $n->type_notification = 'PPIL\models\NotificationIntervention';
            $n->mail_source = $enseignant->mail;
            $n->save();

            $new_notification_intervention = new NotificationIntervention();
            $new_notification_intervention->id_notification = $n->id_notification;

            $new_notification_intervention->heuresCM = $datas['heuresCM'];
            $new_notification_intervention->heuresTD = $datas['heuresTD'];
            $new_notification_intervention->heuresTP = $datas['heuresTP'];
            $new_notification_intervention->heuresEI = $datas['heuresEI'];
            $new_notification_intervention->groupeTD = $datas['groupeTD'];
            $new_notification_intervention->groupeTP = $datas['groupeTP'];
            $new_notification_intervention->groupeEI = $datas['groupeEI'];

            $new_notification_intervention->id_intervention = $id_intervention;
            $new_notification_intervention->id_UE = $id_UE;
            $new_notification_intervention->supprimer = $supprimer;
            $new_notification_intervention->nom_UE = $nom_UE;
            $new_notification_intervention->nom_formation = $nom_formation;

            $new_notification_intervention->save();
            NotificationIntervention::appliquer($new_notification_intervention, $n);
            //  $new_notification_intervention->delete();
            //$n->delete();

        } else {
            $ue = UE::where('id_UE', '=', $id_UE)
                ->first();
            if(isset($ue)){
                # ajout d'une intervention dans la fst, ou modification d'une UE
                $n = new Notification();
                if($id_intervention == null){
                    $n->message = "Ajout intervention";
                } else {
                    $n->message = "Modification intervention";
                }
                $n->mail_source = $enseignant->mail;
                $n->besoin_validation = 1;
                $n->validation = 0;
                $n->type_notification = 'PPIL\models\NotificationIntervention';
                $n->save();

                $new_notification_intervention = new NotificationIntervention();

                $new_notification_intervention->id_notification = $n->id_notification;

                $new_notification_intervention->heuresCM = $datas['heuresCM'];
                $new_notification_intervention->heuresTD = $datas['heuresTD'];
                $new_notification_intervention->heuresTP = $datas['heuresTP'];
                $new_notification_intervention->heuresEI = $datas['heuresEI'];
                $new_notification_intervention->groupeTD = $datas['groupeTD'];
                $new_notification_intervention->groupeTP = $datas['groupeTP'];
                $new_notification_intervention->groupeEI = $datas['groupeEI'];

                $new_notification_intervention->id_intervention = $id_intervention;
                $new_notification_intervention->id_UE = $id_UE;
                $new_notification_intervention->supprimer = $supprimer;
                $new_notification_intervention->nom_UE = $nom_UE;
                $new_notification_intervention->nom_formation = $nom_formation;
                $new_notification_intervention->save();

                if($ue->fst == false){
                    NotificationIntervention::appliquer($new_notification_intervention, $n);
                    //$new_notification_intervention->delete();
                    //$n->delete();
                } else {

                    $resp = Responsabilite::where('intituleResp', '=', 'Responsable UE')
                          ->where('id_UE', '=', $id_UE)
                          ->first();
                    if(empty($resp)){
                        $resp = Responsabilite::where('intituleResp', '=', 'Responsable Formation')
                              ->where('id_formation', '=', $ue->id_formation)
                              ->first();
                        if(empty($resp)){
                            $resp = Responsabilite::where('intituleResp', '=', 'Responsable du departement informatique')
                                  ->first();
                        }
                    }

                    $n->mail_destinataire = Enseignant::where('mail', '=', $resp->enseignant)
                                          ->first()
                                          ->mail;

                    $n->mail_source = $enseignant->mail;
                    $n->save();
                    if(!$supprimer && !$ajout){
                        NotificationIntervention::appliquer($new_notification_intervention, $n);
                    }
                }
            }
        }
    }

	public static function reinitialiserMDP($utilisateur, $nveauMDP_hash){
		$utilisateur->mdp = $nveauMDP_hash;
		$utilisateur->save();
	}

    public static function get_privilege($utilisateur){
        return $max = DB::table('Responsabilite')
                      ->where('enseignant', 'like', $utilisateur->mail)
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
        if(!is_null($user->volumeCourant) && $user->volumeCourant != 0 && $user->volumeMin != 0 ){
            $pourcentage = ($user->volumeCourant / $user->volumeMin)*100;
        }
        return $pourcentage;
    }

	public static function reinitialiserBDD ($mail_enseignant){
		$e = Enseignant::where('mail', '<>', $mail_enseignant)->get();
		foreach($e as $ens){
			$ens->delete();
		}
	}

	public static function desinscription($mail){
		$req = Enseignant::where('mail', 'like', $mail)->first();
		$req->delete();
	}

}