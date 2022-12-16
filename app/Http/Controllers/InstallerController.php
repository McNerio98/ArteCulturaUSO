<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConfigOption;
use Artisan;

class InstallerController extends Controller
{
    /**Generacion de Clave de Aplication */
    public function key_generate(){
        $FLAG = ConfigOption::getOption("MODE_INSTALLER");
        if($FLAG != "A"){echo "Accion no permitida";exit;}

        try{
            Artisan::call('key:generate');
            echo "Key de aplicacion | Configurado correctamente";
        }catch(\Throwable $ex){
            echo "ERROR: [" . $ex->getMessage() . "]";
        }               
    }

    /**Generacion de Link Simbolico para Almacenamiento */
    public function storage_link(){
        $FLAG = ConfigOption::getOption("MODE_INSTALLER");
        if($FLAG != "A"){echo "Accion no permitida";exit;}

        try{
            Artisan::call('storage:link');
            echo "Link storage simbolico | Configurado correctamente";
        }catch(\Throwable $ex){
            echo "ERROR: [" . $ex->getMessage() . "]";
        }             
    }

    /**Clear cache config */
    public function clear_config(){
        $FLAG = ConfigOption::getOption("MODE_INSTALLER");
        if($FLAG != "A"){echo "Accion no permitida";exit;}

        try{
            Artisan::call('config:clear');
            echo "Borrar cache de configuracion | Configurado correctamente";
        }catch(\Throwable $ex){
            echo "ERROR: [" . $ex->getMessage() . "]";
        }        
    }

    /**Clear cache application */
    public function clear_cache(){
        $FLAG = ConfigOption::getOption("MODE_INSTALLER");
        if($FLAG != "A"){echo "Accion no permitida";exit;}

        try{
            Artisan::call('cache:clear');
            echo "Borrar cache de applicacion | Configurado correctamente";
        }catch(\Throwable $ex){
            echo "ERROR: [" . $ex->getMessage() . "]";
        }
    }

    /**Clear cache routes*/
    public function clear_route(){
        $FLAG = ConfigOption::getOption("MODE_INSTALLER");
        if($FLAG != "A"){echo "Accion no permitida";exit;}

        try{
            Artisan::call('route:clear');
            echo "Borrar cache de rutas | Configurado correctamente";
        }catch(\Throwable $ex){
            echo "ERROR: [" . $ex->getMessage() . "]";
        }
    }    

}
