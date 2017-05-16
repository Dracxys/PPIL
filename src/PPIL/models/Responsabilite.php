<?php
namespace PPIL\models;

class Responsabilite extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Responsabilite";
	protected $primaryKey = "id_resp";
	public $timestamps = false;

	public static function ajoutResponsabilite($mail, $intitule_responsabilite, $id_formation, $id_UE){
		$n = new Responsabilite();
		$n->intituleResp = strtolower ($intitule_responsabilite);
		$n->enseignant = $mail;

		if(strcmp(strtolower ($intitule_responsabilite), 'responsable ue')){
			$n->id_UE = $id_UE;
			$n->priorite = 0;
		} else if(strcmp(strtolower ($intitule_responsabilite), 'responsable formation')){
			$n->id_formation = $id_formation;
			$n->priorite = 1;
		} else {
			$n->priorite = 2;
		}
		$n->save();
	}


}