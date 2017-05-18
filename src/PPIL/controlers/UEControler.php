<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 16/05/2017
 * Time: 19:35
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\views\VueUe;
use PPIL\views\VueHome;
use PPIL\views\VueModifProfil;
use PPIL\views\VueUtilisateur;
use PPIL\models\UE;
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use Slim\Slim;


class UEControler
{
    public function home(){
        $u = UE::all();
        $val = array();
        foreach ($u as $value){
            if(!in_array($value->nom_UE,$val)){
                $val[] = $value->nom_UE;
            }
        }
        $v = new VueUe();
        echo $v->home($val);
    }

    public function infoUE(){
        $app = Slim::getInstance();
        $nom = $app->request->post();
        $nom = filter_var($nom['nom'],FILTER_SANITIZE_STRING);
        $for = \PPIL\models\Formation::where('nomFormation','like',$nom)->first();
        $ue = UE::where('id_formation','=',$for->id_formation)->get();
        $res = array();
        if(!empty($ue)){
            foreach ($ue as $value){
                $res[] = $value->nom_UE;
            }
        }
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($res);
    }

    public function intervenantsUE() {
        
    }

    public function creerUE(){
        if(isset($_SESSION['mail'])) {
            $val = Slim::getInstance()->request->post();
            $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);

            $heuresCM = filter_var($val['heuresCM'], FILTER_SANITIZE_STRING);
            $heuresTP = filter_var($val['heuresTP'], FILTER_SANITIZE_STRING);
            $heuresTD = filter_var($val['heuresTD'], FILTER_SANITIZE_STRING);
            $heuresEI = filter_var($val['heuresEI'], FILTER_SANITIZE_STRING);

            $groupeTP = filter_var($val['groupeTP'], FILTER_SANITIZE_STRING);
            $groupeTD = filter_var($val['groupeTD'], FILTER_SANITIZE_STRING);
            $groupeEI = filter_var($val['groupeEI'], FILTER_SANITIZE_STRING);

            $nom_responsable = filter_var($val['nom_responsable'], FILTER_SANITIZE_STRING);

            UE::creerUE($nom, $heuresCM, $heuresTP, $heuresTD, $heuresEI, $groupeTP, $groupeTD, $groupeEI);

            if (!empty($nom_responsable)){
                $responsable = Enseignant::where('nom', 'like', $nom_responsable)->first();
                if(empty(responsable)){
                    // *************************** ERREUR : L'ENSIGNANT N'EXISTE PAS
                } else {
                    $nouvUE = UE::where('nom_UE', 'like', $nom)->first();
                    ajoutResponsabilite($responsable->mail, 'responsable ue', null, $nouvUE->id_UE);
                }
            }

        }
    }
	
	public function modifierUE(){
		if(isset($_SESSION['mail'])) {
			$val = Slim::getInstance()->request->post();
			$id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);
			
			$nouv_nom = filter_var($val['nouv_nom'], FILTER_SANITIZE_STRING);

			$heuresCM = filter_var($val['heuresCM'], FILTER_SANITIZE_STRING);
			$heuresTP = filter_var($val['heuresTP'], FILTER_SANITIZE_STRING);
			$heuresTD = filter_var($val['heuresTD'], FILTER_SANITIZE_STRING);
			$heuresEI = filter_var($val['heuresEI'], FILTER_SANITIZE_STRING);

			$groupeTP = filter_var($val['groupeTP'], FILTER_SANITIZE_STRING);
			$groupeTD = filter_var($val['groupeTD'], FILTER_SANITIZE_STRING);
			$groupeEI = filter_var($val['groupeEI'], FILTER_SANITIZE_STRING);

			$nom_responsable = filter_var($val['nom_responsable'], FILTER_SANITIZE_STRING);

			UE::modifierUE($id, $nouv_nom, $heuresCM, $heuresTP, $heuresTD, $heuresEI, $groupeTP, $groupeTD, $groupeEI);

			$ue = UE::where('id_UE','=',$id)->first();
			$responsabilite = Responsabilite::where('id_UE','=',$ue->id_UE)->first();
			
			/* il n'existe pas déjà un responsable UE mais on en assigne un nouveau */
			if (empty($responsabilite) && !empty($nom_responsable)){ 
				$nouv_responsable = Enseignant::where('nom', 'like', $nom_responsable)->first();
				if(!empty($nouv_responsable)){
				Responsabilite::ajoutResponsabilite($nouv_responsable->mail, 'responsable ue', null, $ue->id_UE);
				Notification::notification_ajout_responsabilite($nouv_responsable->mail, $_SESSION['mail'], $nouv_nom, null);
				} else {
					// *************************** ERREUR : L'ENSIGNANT N'EXISTE PAS
				}
			}
			/* il existe déjà un responsable UE */
			if (!empty($responsabilite)){
				$ancien_responsable = Enseignant::where('mail','like',$responsabilite->enseignant)->first();
				
				/* on le supprime sans le remplacer */
				if (empty($nom_responsable)){
					Notification::notification_supprimer_responsabilite($ancien_responsable->mail, $_SESSION['mail'], $nouv_nom, null);
					Responsabilite::supprimerResponsabilite($ancien_responsable->mail, null, $ue->id_UE);
				} else {
					$nouv_responsable = Enseignant::where('nom', 'like', $nom_responsable)->first();
					/* le nom du responsable appartient à la BDD */
					if (!empty($nouv_responsable)){
						/* le responsable ne change pas */
						if(strcmp($ancien_responsable->mail, $nouv_responsable->mail) == 0){
							Notification::notification_modification_UE_formation($nouv_responsable->mail, $_SESSION['mail'], $nouv_nom, null)
						}
						/* on le remplace par un autre responsable UE */
						if(strcmp($ancien_responsable->mail, $nouv_responsable->mail) != 0) {
							Notification::notification_supprimer_responsabilite($ancien_responsable->mail, $_SESSION['mail'], $nouv_nom, null);
							Responsabilite::supprimerResponsabilite($ancien_responsable->mail, null, $ue->id_UE);
							
							Responsabilite::ajoutResponsabilite($nouv_responsable->mail, 'responsable ue', null, $ue->id_UE);
							Notification::notification_ajout_responsabilite($nouv_responsable->mail, $_SESSION['mail'], $nouv_nom, null);
						}
					} else {
						// *************************** ERREUR : L'ENSIGNANT N'EXISTE PAS
					}
				}
						
			}
		}
	}
	
	
	
}