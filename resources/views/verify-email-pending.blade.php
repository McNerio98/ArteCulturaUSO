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
</style>
@endpush
@section('content')
<main role="main" class="flex-shrink-0 msg-status-request">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <section class="pnl-info">
            <h1 style="font-size: 500%; color: #00cd6a;"><i class="fas fa-paper-plane"></i></h1>
            <h1>Verificación de correo... </h1>
            <p class="lead">Se ha enviado un correo a tu bandeja para que podamos verificar que eres el propietario de la cuenta. El corre fue enviado a:
                    <span class=" font-weight-bold">{{$user_email}}</span></p>
                    <h4><code>Revisar en correo no deseado si no se muestra en la bandeja principal</code></h4>
            <p class="lead">
                <a href="{{route('inicio')}}" class="btn btn-lg btn-secondary">Ir a inicio</a>
            </p>
        </section>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
</main>
@endsection
