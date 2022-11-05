@extends('layouts.admin-template')
@section('title', 'Procesos')
@section('windowName', 'PROCESOS')


@section('content')
<div class="container-fluid" id="appProcesos">        

    <div class="container">
        <div class="row">

            <div class="mt-4 mb-3 col-lg-4 col-md-6">
                <div class="card">
                    <div class="p-3 card-body">
                        <div class="d-flex mt-n2">
                            <div class="p-2 ac_avatar ac_avatar-xl ac_bg-gradient-dark ac_border-radius-xl mt-n4">
                                <img src="{{asset('/images/restore.png')}}" alt="Slack Bot">
                            </div>
                            <div class="ml-3">
                                <h6>Restablecer Eventos</h6>
                            </div> 
                        </div>
                        <p class="mt-3 text-sm">Restablezca las fechas de los eventos que repiten cada año.</p>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" @click="runResetDatesEvents">
                                <i class="fas fa-rocket"></i>
                                    INICIAR PROCESO</a>
                            </div>
                            <div class="col-6 text-right">
                                <h6 class="mb-0 text-sm">02.03.22</h6>
                                <p class="mb-0 text-sm text-secondary font-weight-normal">Última ejecución</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="mt-4 mb-3 col-lg-4 col-md-6">
                <div class="card">
                    <div class="p-3 card-body">
                        <div class="d-flex mt-n2">
                            <div class="p-2 ac_avatar ac_avatar-xl ac_bg-gradient-dark ac_border-radius-xl mt-n4">
                                <img src="{{asset('/images/send.png')}}" alt="Slack Bot">
                            </div>
                            <div class="ml-3">
                                <h6>Test de correo</h6>
                            </div> 
                        </div>
                        <p class="mt-3 text-sm">Envié un correo electrónico para verificar el correcto funcionamiento de las solicitudes.</p>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" @click="runTestEmail">
                                <i class="fas fa-rocket"></i>
                                    INICIAR PROCESO</a>
                            </div>
                            <div class="col-6 text-right">
                                <h6 class="mb-0 text-sm">02.03.22</h6>
                                <p class="mb-0 text-sm text-secondary font-weight-normal">Última ejecución</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            


        </div>
    </div>




</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-procesos.js') }}"></script>
@endpush
