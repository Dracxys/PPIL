<?php
namespace PPIL\models;

class Intervention extends AbstractModel{
	protected $table = "Intervention";
	protected $primaryKey = "id_intervention";
	public $timestamps = false;


	public static function modifierIntervention($inter,$heureCM,$heureTD,$heureTP,$heureEI,$groupeTD,$groupeTP,$groupeEI){
        $inter->heuresCM = $heureCM;
        $inter->heuresTD = $heureTD;
        $inter->heuresTP = $heureTP;
        $inter->heuresEI = $heureEI;
        $inter->groupeTD = $groupeTD;
        $inter->groupeTP = $groupeTP;
        $inter->groupeEI = $groupeEI;
        $inter->save();
        if($inter->id_UE != null && $inter->id_responsabilite == null){
            $ue = UE::find($inter->id_UE);
            UE::recalculer($ue);
        }
        $e = Enseignant::find($inter->mail_enseignant);
	    Enseignant::conversionHeuresTD($e);
    }

	public static function reinitialiserBDD($id_resp){
		$req = Intervention::where('id_responsabilite', '<>', $id_resp)->get();
		foreach($req as $i){
			$i->delete();
		}
	}

	public static function desinscription($mail){
		$req = Intervention::where('mail_enseignant', 'like', $mail)->get();
		foreach($req as $r){
			$r->delete();
		}
	}
}
