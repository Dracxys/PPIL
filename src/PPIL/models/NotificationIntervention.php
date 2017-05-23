<?php
namespace PPIL\models;

class NotificationIntervention extends Notification{
	protected $table = "NotificationIntervention";
	protected $primaryKey = "id_notification";
	public $timestamps = false;
    public $incrementing = false;

	public static function appliquer($notification_intervention, $notification) {
        if(is_null($notification_intervention->id_intervention)){
            # ajout d'une nouvelle intervention
            if($notification_intervention->nom_UE != null && $notification_intervention->nom_formation != null && $notification_intervention->id_UE == null){
                # des noms sont donnés, id_UE et id_formation sont null
                # on crée l'ue, la formation et l'intervention
                $formation = new Formation();
                $formation->nomFormation = $notification_intervention->nom_formation;
                $formation->fst = false;
                $formation->save();

                $ue = new UE();
                $ue->id_formation = $formation->id_formation;
                $ue->nom_UE = $notification_intervention->nom_UE;
                $ue->fst = false;
                $ue->save();

                $intervention = new Intervention();
                $intervention->fst = $ue->fst;
                $intervention->heuresCM = $notification_intervention->heuresCM;
                $intervention->heuresTP = $notification_intervention->heuresTP;
                $intervention->heuresTD = $notification_intervention->heuresTD;
                $intervention->heuresEI = $notification_intervention->heuresEI;
                $intervention->groupeTP = $notification_intervention->groupeTP;
                $intervention->groupeTD = $notification_intervention->groupeTD;
                $intervention->groupeEI = $notification_intervention->groupeEI;
                $intervention->mail_enseignant = $notification->mail_source;
                $intervention->id_UE = $ue->id_UE;
                $intervention->save();
                UE::recalculer($ue);
                Enseignant::conversionHeuresTD(Enseignant::where('mail', 'like', $intervention->mail_enseignant)->first());


            } else if($notification_intervention->nom_UE == null && $notification_intervention->nom_formation == null && $notification_intervention->id_UE != null){
                # pas de noms donnés, id_UE et id_formation sont définis
                $ue = UE::where('id_UE', '=', $notification_intervention->id_UE)
                    ->first();
                $intervention = new Intervention();
                $intervention->fst = $ue->fst;
                $intervention->heuresCM = $notification_intervention->heuresCM;
                $intervention->heuresTP = $notification_intervention->heuresTP;
                $intervention->heuresTD = $notification_intervention->heuresTD;
                $intervention->heuresEI = $notification_intervention->heuresEI;
                $intervention->groupeTP = $notification_intervention->groupeTP;
                $intervention->groupeTD = $notification_intervention->groupeTD;
                $intervention->groupeEI = $notification_intervention->groupeEI;
                $intervention->mail_enseignant = $notification->mail_source;
                $intervention->id_UE = $ue->id_UE;
                $intervention->save();
                UE::recalculer($ue);
                Enseignant::conversionHeuresTD(Enseignant::where('mail', 'like', $intervention->mail_enseignant)->first());
            }
        } else {
            # modification d'une intervention existante
            $intervention = Intervention::where('id_intervention', '=', $notification_intervention->id_intervention)
                          ->first();

            $ue = UE::where('id_UE', '=', $notification_intervention->id_UE)
                ->first();


            if(!empty($intervention) && !empty($ue)){
                if($notification_intervention->supprimer){
                    $intervention->delete();
                    UE::recalculer($ue);
                    Enseignant::conversionHeuresTD(Enseignant::where('mail', 'like', $intervention->mail_enseignant)->first());
                } else {
                    $intervention->heuresCM = $notification_intervention->heuresCM;
                    $intervention->heuresTP = $notification_intervention->heuresTP;
                    $intervention->heuresTD = $notification_intervention->heuresTD;
                    $intervention->heuresEI = $notification_intervention->heuresEI;
                    $intervention->groupeTP = $notification_intervention->groupeTP;
                    $intervention->groupeTD = $notification_intervention->groupeTD;
                    $intervention->groupeEI = $notification_intervention->groupeEI;
                    $intervention->save();
                    UE::recalculer($ue);
                    Enseignant::conversionHeuresTD(Enseignant::where('mail', 'like', $intervention->mail_enseignant)->first());
                }
            }
        }

        $notification_intervention->delete();
        $notification->delete();

    }
	
	public static function reinitialiserBDD(){
		$req = NotificationIntervention::all();
		foreach($i as $req){
			$i->delete();
		}
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