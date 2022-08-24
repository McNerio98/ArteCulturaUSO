@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appMemoryShow">
    <input type="hidden" id="idmemory" value="{{request('id')}}">
    <div class="container">
        <memory v-for="(e,index) of modelo" :pdata="e"/>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-memories.js') }}"></script>
@endpush
