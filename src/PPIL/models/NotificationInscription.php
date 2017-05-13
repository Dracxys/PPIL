<?php
namespace PPIL\models;

class NotificationInscription extends Notification{
	protected $table = "NotificationInscription";
	protected $primaryKey = "id_notification";
	public $timestamps = false;

    public function notification(){
        return $this->morphOne('PPIL\models\Notification', 'notification', 'type_notification', 'id_notification');
    }
}

/*
exemple d'utilisation :
    $n = new Notification();
    $n->message = "plop";
    $n->besoin_validation = 1;
    $n->validation = 0;
    $n->nomUE = "a";
    $n->type_notification = 'PPIL\models\NotificationInscription';
    $n->save();


    $n2 = new NotificationInscription();
    $n2->nom = "c";
    $n2->prenom="c";
    $n2->statut="Enseignant-chercheur permanent";
    $n2->mail = "c";
    $n2->mot_de_passe = "o";
    $n2->save();
    $n2->notification()->save($n);


    $ni = NotificationInscription::find(1);
    echo $ni->notification->message;
 */