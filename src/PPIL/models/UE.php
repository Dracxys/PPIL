<?php
namespace PPIL\models;

class UE extends \Illuminate\Database\Eloquent\Model{
	protected $table = "UE";
	protected $primaryKey = "id_UE";
    public $incrementing = false;
	
	public static function creerUE($nom, $heuresCM, $heuresTP, $heuresTD, $heuresEI, $groupeTP, $groupeTD, $groupeEI){
		$n = new UE();
		$n->nom_UE = $nom;
		
		$n->prevision_heuresTD = $heuresTD;
		$n->prevision_heuresTP = $heuresTP;
		$n->prevision_heuresCM = $heuresCM;
		$n->prevision_heuresEI = $heuresEI;
		
		$n->prevision_groupeTD = $groupeTD;
		$n->prevision_groupeTP = $groupeTP;
		$n->prevision_groupeEI = $groupeEI;
		
		$n->save();
	}
	
	
	
}