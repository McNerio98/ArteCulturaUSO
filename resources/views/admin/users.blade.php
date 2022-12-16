@extends('layouts.admin-template')
@section('title', 'Usuarios')
@section('windowName', 'USUARIOS REGISTRADOS ')


@section('PanelTitle', 'Usuarios')
@section('PanelSubtitle', 'Registros')


@section('content')
<div class="container-fluid" id="appUsersAdmin">
        <input type="hidden" id="hFilterUser" value="{{app('request')->input('filter')}}">
		<div class="mb-1 mb-md-4">
			<div class="custom-control custom-radio">
				<input @click="getByFilter('all')" :checked="filters.users == 'all' " class="custom-control-input" type="radio" id="customRadio1" name="customRadio">
				<label for="customRadio1" class="custom-control-label">Todos los usuarios</label>
			</div>
			<div class="custom-control custom-radio">
				<input @click="getByFilter('request')" :checked="filters.users == 'request' " class="custom-control-input" type="radio" id="customRadio2" name="customRadio">
				<label for="customRadio2" class="custom-control-label">Solicitudes</label>
			</div>
			<div class="custom-control custom-radio">
				<input @click="getByFilter('enabled')" :checked="filters.users == 'enabled' " class="custom-control-input" type="radio" id="customRadio3" name="customRadio">
				<label for="customRadio3" class="custom-control-label">Habilitados</label>
			</div>						
			<div class="custom-control custom-radio">
				<input @click="getByFilter('disabled')" :checked="filters.users == 'disabled' " class="custom-control-input" type="radio" id="customRadio4" name="customRadio">
				<label for="customRadio4" class="custom-control-label">Inhabilitados</label>
			</div>									
		</div>		
        <div class="row">
            <contact v-for="e in user_list" :key="e.id" :user="e"></contact>            
        </div>

        <!--PAGINATION-->
        <div v-if="showPagination">
            <nav aria-label="Navegacion elementos">
                <ul class="pagination justify-content-center">
                    <li v-bind:class="{'disabled' : ! (pagination.current_page > 1)}" class="page-item">
                        <a @click.prevent="changePage(pagination.current_page - 1)" class="page-link" href="#">Anterior</a>
                    </li>
                    <li v-for="page in pagesNumber" v-bind:key="page" v-bind:class="[page == isActive? 'active':'']" class="page-item">
                        <a @click.prevent="changePage(page)" class="page-link" href="#">@{{page}}</a>
                    </li>
                    <li v-bind:class="{'disabled' : ! (this.pagination.current_page < this.pagination.last_page)}" class="page-item">
                        <a @click.prevent="changePage(pagination.current_page + 1)" class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>           
        </div>
        <!--END PAGINATION-->        
</div>
<!--/. container-fluid -->  
@endsection


@Push('customScript')
    <script src="{{ asset('js/admin/app-users.js') }}"></script>
@endpush
