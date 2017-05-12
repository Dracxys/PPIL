<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 12/11/2015
 * Time: 10:17
 */

namespace PPIL\utils;

use Illuminate\Database\Capsule\Manager as DB;

class ConnectionFactory
{

    public static $v;

    public static function setConfig($file){
        static::$v = parse_ini_file($file);
    }

    public static function makeConnection(){
        $db = new DB();
        $db->addConnection(ConnectionFactory::$v);
        $db->setAsGlobal();
        $db->bootEloquent();
    }

}