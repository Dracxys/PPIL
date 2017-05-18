<?php
/**
 * Created by PhpStorm.
 * User: tjano
 * Date: 18/05/2017
 * Time: 14:09
 */

namespace PPIL\models;


class NotificationResponsabilite extends Notification
{
    protected $table = "NotificationResponsabilite";
    protected $primaryKey = "id_notification";
    public $timestamps = false;
}