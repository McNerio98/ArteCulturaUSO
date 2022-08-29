@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesIndex">
    <div class="container">
        <div class="row">
            <no-records v-if="items.length == 0" icon="box.svg" page="Recursos"></no-records>
            <resource-summary v-else v-for="(e) in items" :pdata="e" :key="e.id" @on-read="onReadResource"/>
        </div>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-resources.js') }}"></script>
@endpush
