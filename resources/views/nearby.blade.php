@extends('layouts.general-template')
@section('title', 'Cercanos')

@section('content')
<main role="main" class="flex-shrink-0" id="appNearbyFront">
    <div class="container">
        
        <gps-request 
            url-back="{{route('inicio')}}"
            v-if="isRequestActive" 
            @on-active="onActive" 
            @on-denied="onDenied">
        </gps-request>

        <div v-if="isGettingItems">
            <spinner1 label="Consultando ..."/>
        </div>

        <div class="mt-3" v-else>
            <div class="row">
                <postevent-card 
                v-for="(e) in finalNearby" 
                :pdata="e"
                @selected="onSelected"></postevent-card>
            </div>
        </div>

        <div class="row" v-if="!isGettingItems && finalNearby.length == 0 && !isRequestActive">
            <div class="col-12">
                <nodata-custom 
                    header="No se encontraron eventos cercanos"
                    title="¡¡Espéralos muy pronto!!"
                    subtitle="Sin eventos cerca de tu ubicación actual"
                    icon="box.svg"></nodata-custom>
            </div>
        </div>        
        
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-nearby.js') }}"></script>
@endpush
