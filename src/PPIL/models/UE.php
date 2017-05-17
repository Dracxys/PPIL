<?php
namespace PPIL\models;

class UE extends \Illuminate\Database\Eloquent\Model{
	protected $table = "UE";
	protected $primaryKey = "id_UE";
    public $incrementing = false;
	public $timestamps = false;

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

    public static function recalculer($e){
        $interventions = Intervention::where('id_UE', '=', $e->id_UE)
                       ->get();
        /*
        $e->heuresCM = 0;
        $e->heuresTP = 0;
        $e->heuresTD = 0;
        $e->heuresEI = 0;
        $e->groupeTP = 0;
        $e->groupeTD = 0;
        $e->groupeEI = 0;
*/
        $heuresCM = 0;
        $heuresTP = 0;
        $heuresTD = 0;
        $heuresEI = 0;
        $groupeTP = 0;
        $groupeTD = 0;
        $groupeEI = 0;

        foreach($interventions as $intervention){
            $heuresCM += $intervention->heuresCM;
            $heuresTP += $intervention->heuresCM;
            $heuresTD += $intervention->heuresCM;
            $heuresEI += $intervention->heuresCM;
            $groupeTP += $intervention->heuresCM;
            $groupeTD += $intervention->heuresCM;
            $groupeEI += $intervention->heuresCM;
        }

        $groupeTP = count(Intervention::distinct()
                          ->select('groupeTP')
                          ->where('id_UE', '=', $e->id_UE)
                          ->groupBy('groupeTP')
                          ->get());

        $groupeTD = count(Intervention::distinct()
                          ->select('groupeTD')
                          ->where('id_UE', '=', $e->id_UE)
                          ->groupBy('groupeTD')
                          ->get());

        $groupeEI = count(Intervention::distinct()
                          ->select('groupeEI')
                          ->where('id_UE', '=', $e->id_UE)
                          ->groupBy('groupeEI')
                          ->get());

        $e->heuresCM = $heuresCM;
        $e->heuresTP = $heuresTP;
        $e->heuresTD = $heuresTD;
        $e->heuresEI = $heuresEI;
        $e->groupeTP = $groupeTP;
        $e->groupeTD = $groupeTD;
        $e->groupeEI = $groupeEI;
        $e->save();

    }



}