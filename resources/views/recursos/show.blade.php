@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesShow">
    <input type="hidden" id="idresource" value="{{request('id')}}">
    <div class="container">
        <resource v-for="(e,index) in modelo" :pdata="e" :key="e.id" @on-edit="onEditResource"/> 
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-resources.js') }}"></script>
@endpush
