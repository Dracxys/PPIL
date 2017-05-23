<?php
namespace PPIL\models;

class Intervention extends AbstractModel{
	protected $table = "Intervention";
	protected $primaryKey = "id_intervention";
	public $timestamps = false;


	public static function modifierIntervention($inter,$heureCM,$heureTD,$heureTP,$heureEI,$groupeTD,$groupeTP,$groupeEI){
	    $ue = UE::find($inter->id_UE);
        $inter->heuresCM = $heureCM;
        $inter->heuresTD = $heureTD;
        $inter->heuresTP = $heureTP;
        $inter->heuresEI = $heureEI;
        $inter->groupeTD = $groupeTD;
        $inter->groupeTP = $groupeTP;
        $inter->groupeEI = $groupeEI;
        $inter->save();
	    UE::recalculer($ue);
        $e = Enseignant::find($inter->mail_enseignant);
	    Enseignant::conversionHeuresTD($e);
    }
	
	public static function reinitialiserBDD(){
		$req = Intervention::all();
		foreach($req as $i){
			$i->delete();
		}
	}
}
