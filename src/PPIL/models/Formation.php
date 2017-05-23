<?php
namespace PPIL\models;

class Formation extends AbstractModel{
	protected $table = "Formation";
	protected $primaryKey = "id_formation";
    public $timestamps = false;

    public static function creerForm($nom,$fst){
        $form = new Formation();
        $form->nomFormation = $nom;
        $form->fst = $fst;
        $form->save();
        return $form->id_formation;

    }
	
	public static function reinitialiserBDD(){
		$f = Formation::all();
		foreach($f as $formations){
			$formations->delete();
		}
	}

}