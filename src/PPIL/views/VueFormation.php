<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 16/05/2017
 * Time: 11:17
 */

namespace PPIL\views;


class VueFormation extends AbstractView
{
    public function home($u){
        $html  = self::headHTML();
        $html .= self::navHTML("Formation");
        $select = self::selectStatut($u);
        $html .= <<< END
        <div id="formation" class="panel-body">
            <h2 class="panel-heading text-center">Formation</h2>
            <div id="selectForm" class="panel-group">
                $select
            </div>
            
        </div>
END;
        return $html;

    }

    public static function selectStatut($for)
    {
        $html = '<select class="form-control" name="form">';
        $i = 0;
        foreach ($for as $value) {
            if ($i == 0) {
                $html .= '<option selected value=' . '"' . $value->nomFormaion . '"' . '>' . $value->nomFormaion . '</option>';
                $i ++;
            } else {
                $html .= '<option value=' . '"' . $value->nomFormaion . '"' . '>' . $value->nomFormaion . '</option>';
            }
        }
        $html .= "</select>";
        return $html;
    }
}