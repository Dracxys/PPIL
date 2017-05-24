<?php
namespace PPIL\models;

class Notification extends AbstractModel{
	protected $table = "Notification";
	protected $primaryKey = "id_notification";
	public $timestamps = true;


	public static function notification_ajout_responsabilite($mail_destinataire, $mail_source, $UE, $formation){
		$n = new Notification();
		$n->mail_destinataire = $mail_destinataire;
		$n->mail_source = $mail_source;
		$n->besoin_validation = false;
		/* UE = 1 : message ajout responsabilité UE */
		if (isset($UE)){
			$n->message = "Vous êtes maintenant Responsable de l'UE ".$UE.".";
		}
		/* UE = 0 : message ajout responsabilité formation */
		if (isset($formation)){
			$n->message = "Vous êtes maintenant Responsable de la formation ".$formation.".";
		}
		$n->save();
	}

	public static function notification_supprimer_responsabilite($mail_destinataire, $mail_source, $UE, $formation){
		$n = new Notification();
		$n->mail_destinataire = $mail_destinataire;
		$n->mail_source = $mail_source;
		$n->besoin_validation = false;
		/* UE = 1 : message ajout responsabilité UE */
		if (isset($UE)){
			$n->message = "Vous n'êtes plus Responsable de l'UE ".$UE.".";
		}
		/* UE = 0 : message ajout responsabilité formation */
		if (isset($formation)){
			$n->message = "Vous n'êtes plus Responsable de la formation ".$formation.".";
		}
		$n->save();
	}


	public static function notification_modification_UE_formation($mail_destinataire, $mail_source, $UE, $formation){
		$n = new Notification();
		$n->mail_destinataire = $mail_destinataire;
		$n->mail_source = $mail_source;
		$n->besoin_validation = false;
		/* UE = 1 : message ajout responsabilité UE */
		if (isset($UE)){
			$n->message = "Les informations sur l'UE ".$UE." ont été modifiées.";
		}
		/* UE = 0 : message ajout responsabilité formation */
		if (isset($formation)){
			$n->message = "Les informations sur la formation ".$formation." ont été modifiées.";
		}
		$n->save();
	}
	
	public static function reinitialiserBDD(){
		$req = Notification::all();
		foreach($req as $i){
			$i->delete();
		}
	}
	
	public static function getNotification($mail){
		$req = Notification::where('mail_destinataire', 'like', $mail)->get();
		return $req;
	}
	
}