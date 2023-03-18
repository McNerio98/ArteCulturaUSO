@extends('layouts.general-template')
@section('title', 'Solicitud de cuenta')

@Push('styles')
<style>
      .msg-status-request{
        height: calc(100% - 90px);
        display: -ms-flexbox !important;
        display: flex !important;
        flex-direction: column;
         justify-content: center;
        align-items: center;
      }

      .msg-status-request .pnl-info{
          text-align: center;
          max-width: 42em;
      }    

      .list-cases-support{
        display: flex;
        flex-direction: column;
        align-items: baseline;
        color: #f84411;
        font-size: 120%;
        margin-bottom: 20px;
      }
      
</style>
@endpush
@section('content')
<main role="main" class="flex-shrink-0 msg-status-request">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <section class="pnl-info">
            <h1 style="font-size: 500%; color: #00cd6a;"><i class="fas fa-headset"></i></h1>
            <h1>Soporte | Contactos</h1>
            <p class="lead" style="text-align: left !important;">Si tienes uno de los siguientes problemas/solicitudes:</p>
            <div class="list-cases-support">
                <span class="font-weight-normal"><i class="fas fa-tags"></i> Reactivación de cuenta</span>
                <span class="font-weight-normal"><i class="fas fa-tags"></i> Reasignación de contraseña</span>
                <span class="font-weight-normal"><i class="fas fa-tags"></i> Reportar error en el sistema</span>
                <span class="font-weight-normal"><i class="fas fa-tags"></i> Etc.</span>
            </div>
            <p class="lead" style="text-align: left !important;" id="textSupport"></p>
            <p class="lead">
                <a href="{{route('inicio')}}" class="btn btn-lg btn-secondary">Ir a inicio</a>
            </p>
        </section>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
</main>
<script>
    window.page_params = {!! json_encode([
        "SOPORTE_EMAIL" => AcHelper::getOption('SOPORTE_EMAIL','NO ESPECIFICADO'),
        "SOPORTE_NUM" => AcHelper::getOption('SOPORTE_NUM',''),
    ]) !!};        

    window.getPage_param = function(param_key , defvalue){
        let param_value = null;
        if(window.page_params == undefined){
            return defvalue;
        }    

        return window.page_params[param_key] != undefined ? window.page_params[param_key] : defvalue;
    }

    document.addEventListener("DOMContentLoaded", function(event) { 
        const textSupport = document.getElementById('textSupport');
        const emailParam = getPage_param('SOPORTE_EMAIL','');
        const numParam = getPage_param('SOPORTE_NUM','0000-0000');

        var strContent = `Te invitamos a contactarnos mediante el correo <span class="font-weight-bold">${emailParam}</span>`;
        if(numParam.trim().length > 0 && numParam != '0000-0000'){
            strContent += ` o el número de teléfono <span class="font-weight-bold">${numParam}</span>`;
        }
        textSupport.innerHTML = strContent;
    });
</script>     
@endsection
