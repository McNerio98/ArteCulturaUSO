@extends('layouts.admin-template')
@section('title', 'Procesos')
@section('windowName', 'PROCESOS')


@section('content')
<div class="container-fluid" id="appPromoIndex">        
contenido
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-promociones.js') }}"></script>
@endpush
