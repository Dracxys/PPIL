<?php
namespace PPIL\models;

class Formation extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Formation";
	protected $primaryKey = "nomFormation";
    public $incrementing = false;
    public $timestamps = false;

}