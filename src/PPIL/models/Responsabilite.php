<?php
namespace PPIL\models;

class Responsabilite extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Responsabilite";
	protected $primaryKey = "id_resp";
	public $timestamps = false;

	public static function ajoutResponsabilite($mail, $intitule_responsabilite, $id_formation, $id_UE){
		$n = new Responsabilite();
		$n->intituleResp = strtolower($intitule_responsabilite);
		$n->enseignant = $mail;
        $n->id_UE = $id_UE;
        $n->id_formation = $id_formation;
		if($n->id_UE != null){

			$n->privilege = 0;
		} else if($n->id_formation != null){
			$n->privilege = 1;
		}
		$n->save();
	}

	public static function supprimerResponsabilite($mail_enseignant, $id_formation, $id_UE){
		if (!empty($id_formation)){
			$n = Responsabilite::where('enseignant', 'like', $mail_enseignant);
			$responsabilite = Responsabilite::where('enseignant', 'like', $nom_enseignant)
											->where('id_formation', '=', $id_formation)
											->get();
			$responsabilite->delete();
		}

		if (!empty($id_UE)){
			$n = Responsabilite::where('enseignant', 'like', $mail_enseignant);
			$responsabilite = Responsabilite::where('enseignant', 'like', $nom_enseignant)
											->where('id_UE', '=', $id_UE)
											->get();
			$responsabilite->delete();
		}
	}

}