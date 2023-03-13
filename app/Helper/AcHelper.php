<?php
namespace App\Helper;
use App\ConfigOption;

class AcHelper
{
    public static function getDatetimeInterval($target_date){
        $now = new \DateTime();
        $target = new \DateTime($target_date);
        $result = $now->diff($target); 
        $temp = "";
        if($result->y > 0){
            $temp = ($result->y == 1) ? " año": " años";
            return "Hace ".$result->y.$temp;
        }

        if($result->m > 0){
            $temp = ($result->m == 1) ? " mes": " meses";
            return "Hace ".$result->m.$temp;            
        }

        if($result->d > 0){
            $temp = ($result->d == 1) ? " día": " días";
            return "Hace ".$result->d.$temp;            
        }

        if($result->h > 0){
            $temp = ($result->h == 1) ? " hora": " horas";
            return "Hace ".$result->h.$temp;            
        }

        if($result->i > 0){
            $temp = ($result->i == 1) ? " minuto": " minutos";
            return "Hace ".$result->i.$temp;            
        }        

        return "Hace un momento";
    }

    public static function getOption($param,$default){
        $option = ConfigOption::getOption($param);
        return is_null($option) ? $default : $option;
    }


}