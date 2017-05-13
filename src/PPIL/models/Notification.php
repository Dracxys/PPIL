<?php
namespace PPIL\models;

abstract class Notification extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Notification";
	protected $primaryKey = "id_notification";
	public $timestamps = false;

}