<?php
namespace PPIL\models;

class Responsabilite extends AbstractModel{
	protected $table = "Responsabilite";
	protected $primaryKey = "id_resp";
	public $timestamps = false;

	public static function ajoutResponsabilite($mail, $intitule_responsabilite, $id_formation, $id_UE){
		$r = new Responsabilite();
		$r->intituleResp = strtolower($intitule_responsabilite);
		$r->enseignant = $mail;
        $r->id_UE = $id_UE;
        $r->id_formation = $id_formation;

		if($r->id_UE != null){
			$r->privilege = 0;
        } else if($r->id_formation != null){
			$r->privilege = 1;
		}
		$r->save();

        # ajoute une intervention liée à sa responsabilité
        $i = Intervention::where('id_responsabilite', '=', $r->id_resp)->first();
        if(empty($i)){
            $i = new Intervention();
            $i->id_responsabilite = $r->id_resp;
        }
        $i->fst = true;
        $i->mail_enseignant = $mail;
        $i->save();

	}

	public static function modifResponsabilite($id_resp, $mail, $intitule_responsabilite, $id_formation, $id_UE){
		$r = Responsabilite::find($id_resp);
        if(empty($r)){
            $r->intituleResp = strtolower($intitule_responsabilite);
            $r->enseignant = $mail;
            $r->id_UE = $id_UE;
            $r->id_formation = $id_formation;

            if($r->id_UE != null){
                $r->privilege = 0;
            } else if($r->id_formation != null){
                $r->privilege = 1;
            }
            $r->save();

            # ajoute une intervention liée à sa responsabilité
            $i = Intervention::where('id_responsabilite', '=', $r->id_resp)->first();
            if(empty($i)){
                $i = new Intervention();
                $i->id_responsabilite = $r->id_resp;
            }
            $i->fst = true;
            $i->mail_enseignant = $mail;
            $i->save();
        }

	}

	public static function supprimerResponsabilite($mail_enseignant, $id_formation, $id_UE){
		if (!empty($id_formation)){

			$responsabilite = Responsabilite::where('enseignant', 'like', $mail_enseignant)
											->where('id_formation', '=', $id_formation)
											->first();
            $i = Intervention::where('id_responsabilite', '=', $responsabilite->id_resp)->first();
            $i->delete();
			$responsabilite->delete();

		}

		if (!empty($id_UE)){
			$responsabilite = Responsabilite::where('enseignant', 'like', $mail_enseignant)
											->where('id_UE', '=', $id_UE)
											->first();
            $i = Intervention::where('id_responsabilite', '=', $responsabilite->id_resp)->first();
            $i->delete();
			$responsabilite->delete();
		}
	}

	public static function reinitialiserBDD(){
		$req = Responsabilite::where('intituleResp', '<>', 'Responsable du departement informatique')->get();
		$r = Responsabilite::where('intituleResp', '=', 'Responsable du departement informatique')->first();
        $interventions = Intervention::where('id_responsabilite', '<>', $r->id_resp)->get();

		foreach($interventions as $i){
            $i->delete();
        }
		foreach($req as $r){
			$r->delete();
		}
	}

	public static function desinscription($mail){
		$req = Responsabilite::where('enseignant', 'like', $mail)->get();
        $interventions = Intervention::where('mail_enseignant', 'like', $mail)->get();
		foreach($interventions as $i){
            $i->delete();
        }
		foreach($req as $r){
			$r->delete();
		}
	}

}