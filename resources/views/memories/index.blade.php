@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appMemoryIndex">
    <div class="container">
        <div class="row">
            <memory-summary v-for="(e,index) of items" :key="e.memory.id" :pdata="e" @on-read="onReadMemory"/>
        </div>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-memories.js') }}"></script>
@endpush
