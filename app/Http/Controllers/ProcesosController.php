<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\UsersHelper;
use App\DtlEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Validator;

class ProcesosController extends Controller
{
    public function index(){
        $request_users = UsersHelper::usersRequest();
        return view('admin.procesos' , ['ac_option' =>'procesos' , 'request_users' => $request_users]);        
    }

    public function testemail(Request $request){
        $output = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        $validator = Validator::make($request->all(),[
            "email" => "required|email"
        ]);


        if($validator->fails()){
            $output["msg"] = "Valores imcompletos";
            return $output;
        }


        $tosend = $request->email;

        try{
            $dataemail = new \stdClass();
            $dataemail->email = $tosend;
            Mail::to($dataemail->email)->send(new TestEmail($dataemail));
            $output["code"] = 1;
            $output["msg"] = "Test de verificacion con exito";

        }catch(\Throwable $ex){
            $output["msg"] = "Ocurrio un error: [".$ex->getMessage()."]";
            $output["msg"] .= " , consulte soporte tecnico";
        }
        
        return $output;
    }

    public function resetdatesevent(Request $request){
        $output = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];


        $items_counts = 0;
        try{
            $rows_affected = 0;
            date_default_timezone_set('America/El_Salvador');        
            $today = date("Y-m-d") . " 00:00:00";
            $events = DtlEvent::where('frequency','repeat')
            ->where('event_date','<',$today)
            ->get();
            $items_counts = count($events);
            foreach($events as $e){
                $datemod = date('Y-m-d H:i:s',strtotime($e->event_date.' +1 year'));
                $e->event_date = $datemod;
                $e->save();
                $rows_affected++;
            }
            $output["data"] = [
                "total" => $items_counts,
                "completed" => $rows_affected
            ];            
            $output["code"] = 1;
            $output["msg"] = "Completado";
        }catch(\Throwable $ex){
            //$output["msg"] = "Error en la operación, consulte soporte técnico.";
            //$output['msg'] = "Error: " . $e->getMessage(); //for debugin
            $output["data"] = [
                "total" => $items_counts,
                "completed" => $rows_affected
            ];
            $output["msg"] = "No se lograron completar todos los items";
        }
        
        return $output;
    }

}
