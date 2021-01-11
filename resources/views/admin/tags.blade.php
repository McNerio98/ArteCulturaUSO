@extends('layouts.admin-template')

@Push('customStyles')
<style>
    .ac-tag {
        padding: 5px 10px;
        border: 1px solid gray;
        border-radius: 5px;
        margin-right: 10px;
        display: inline-block;
        margin-top: 10px;
    }

    .ac-tag .icon{
        display: inline-block;
        margin-left:5px;
    }

    .ac-tag .icon:hover{
        cursor: pointer;
        color: gray;
    }

    .ac-tag input[type="text"]{
        background-color: white;
        border: 0px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid" id="tags">
        <pnl-tags></pnl-tags>    
</div>
<!--/. container-fluid --> 
@endsection


@Push('customScript')
    <script src="{{ asset('js/app-tags.js') }}"></script>
@endpush


