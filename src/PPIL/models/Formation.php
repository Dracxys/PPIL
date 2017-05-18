<?php
namespace PPIL\models;

class Formation extends \Illuminate\Database\Eloquent\Model{
	protected $table = "Formation";
	protected $primaryKey = "id_formation";
    public $timestamps = false;

    public static function creerForm($nom,$fst){
        $form = new Formation();
        $form->nomFormation = $nom;
        $form->fst = $fst;
        $form->save();

    }

}