@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesShow">
    <input type="hidden" id="idresource" value="{{request('id')}}">
    <div class="container">
        <resource v-for="(e,index) in modelo" :pdata="e" :key="e.id" 
        @source-files="onSources" 
        @on-edit="onEditResource"/> 
    </div>
    <media-viewer ref="mediaviewer"/>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-resources.js') }}"></script>
@endpush
