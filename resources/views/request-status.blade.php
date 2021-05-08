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
            <h1 style="font-size: 500%; color: #00cd6a;"><i class="fas fa-thumbs-up"></i></h1>
            <h1>¡Tu cuenta ha sido creada! </h1>
            <p class="lead">Felicidades, <code>{{$user_name}}</code> se ha creado tu cuenta, nos pondremos en contacto <span>vía
                    email o
                    número telefónico</span> lo más pronto posible y te brindaremos tus <span
                    class=" font-weight-bold">credenciales de acceso.</span></p>
            <p class="lead">
                <a href="{{route('inicio')}}" class="btn btn-lg btn-secondary">Ir a inicio</a>
            </p>
        </section>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
</main>
@endsection
