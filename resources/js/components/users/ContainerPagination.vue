<template>
	<div>
		<div>
			<div class="custom-control custom-radio">
				<input class="custom-control-input" type="radio" id="customRadio1" name="customRadio">
				<label for="customRadio1" class="custom-control-label">Todos los usuarios</label>
			</div>
			<div class="custom-control custom-radio">
				<input class="custom-control-input" type="radio" id="customRadio2" name="customRadio">
				<label for="customRadio2" class="custom-control-label">Solicitudes</label>
			</div>
			<div class="custom-control custom-radio">
				<input class="custom-control-input" type="radio" id="customRadio3" name="customRadio">
				<label for="customRadio3" class="custom-control-label">Habilitados</label>
			</div>						
			<div class="custom-control custom-radio">
				<input class="custom-control-input" type="radio" id="customRadio4" name="customRadio">
				<label for="customRadio4" class="custom-control-label">Inhabilitados</label>
			</div>									
		</div>
		<div class="row">
			<contact v-for="e in user_list" :key="e.id" :user="e"></contact>
		</div>
	</div>
</template>

<script>
	export default{
		data(){
			return {
				user_list: [],
				paginate: {
	        		'total': 0,
	        		'current_page':0,
	        		'per_page': 0,
	        		'last_page': 0,
	        		'from': 0,
	        		'to': 0				
				},
				rangesPages: [6,12,24],
				globalPage: 1,
				globalRange: 5				 				
			}
		},
		computed: {
			isActive(){
				return this.paginate.current_page;
			},			
			pagesNumber(){
				if(!this.paginate.to){
					return [];
				}

				var from = this.paginate.current_page - 2; //TODO offset
				if(from < 1){
					from = 1;
				}

				var to = from + (2 + 2); //TODO
				if(to >= this.paginate.last_page){
					to = this.paginate.last_page;
				}

				var pagesArray = [];
				while(from <= to){
					pagesArray.push(from);
					from++;
				}
				return pagesArray;
			}			
		},
		mounted(){
			//this.loadData();
		},
		methods: {
			loadData(page = 1, per_page = 15){
				axios(`/api/users?page=${page}&per_page=${per_page}`).then((result)=>{
					this.user_list = result.data.users.data;
					this.paginate = result.data.paginate;

				}).catch((ex)=>{
					console.log(ex);
				});
			},
	    	changePage(pg){
	    		this.globalPage = pg;
	    		this.paginate.current_page = this.globalPage;
	    		this.loadData(pg,this.globalRange);
	    	},

	    	changeRange(e){
	    		this.globalRange = e.target.value;
	    		this.loadData(this.globalPage,this.globalPage);
	    	}			
		}
	}
</script>