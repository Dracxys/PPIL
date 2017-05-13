<?php
namespace PPIL\models;

class Notification extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Notification";
	protected $primaryKey = "id_notification";
	public $timestamps = true;

    public function notification_specialisee(){
        return $this->morphTo();
    }
}