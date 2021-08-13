@extends('layouts.admin-template')
@section('title', 'Recursos')
@section('windowName', 'RECURSOS VIRTUALES')


@section('content')
<div class="container-fluid" id="appResources">
        <building-page page="Recursos virtuales"></building-page>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-resources.js') }}"></script>
@endpush
