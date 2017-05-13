<?php
namespace PPIL\models;

class Enseignant extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Enseignant";
	protected $primaryKey = "mail";
	public $timestamps = false;

}