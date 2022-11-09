@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appMemoryShow">
    <input type="hidden" id="idmemory" value="{{request('id')}}">
    <div class="container">
        <memory v-for="(e,index) of modelo" :key="index" @source-files="onSources" :pdata="e"/>
    </div>
    <media-viewer ref="mediaviewer"/>    
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-memories.js') }}"></script>
@endpush
