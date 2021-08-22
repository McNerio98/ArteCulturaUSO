@extends('layouts.admin-template')
@section('title', 'Reseñas')
@section('windowName', 'HOMENAJES / BIOGRAFÍAS ')


@section('content')
<div class="container-fluid" id="appMemories">        
        <div class="container">
           <div>
          
           <memory-create></memory-create>
           </div>
        </div>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
