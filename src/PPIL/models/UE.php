<?php
namespace PPIL\models;

class UE extends \Illuminate\Database\Eloquent\Model{
	protected $table = "UE";
	protected $primaryKey = "nom_UE";
	public $timestamps = false;
    public $incrementing = false;
}