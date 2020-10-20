<template>
    <div class="card card-solid">
        <div class="card-header">
            <!--Select per_page-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4"></label>
                    <select @change="changeRange($event)" class="form-control form-control-sm">
                        <option v-for="e in rangesPages" :value="e">{{e}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body pb-0">
            <div class="row d-flex align-items-stretch">
                <contact v-for="e in user_list" :key="e.id" :user="e"></contact>
            </div>
        </div>
        <div class="card-footer">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li v-bind:class="{'disabled' : ! (this.paginate.current_page > 1)}" class="page-item">
                        <a @click.prevent="changePage(paginate.current_page - 1)" class="page-link" href="#">Anterior</a>
                    </li>
                    <li v-for="page in pagesNumber" v-bind:class="[page == isActive? 'active':'']" class="page-item">
                        <a @click.prevent="changePage(page)" class="page-link" href="#">{{page}}</a>
                    </li>
                    <li v-bind:class="{'disabled' : ! (this.paginate.current_page < this.paginate.last_page)}" class="page-item">
                        <a @click.prevent="changePage(paginate.current_page + 1)" class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
	export default{
		props:{
			pathaction: ""
		},
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
			//showLoadingAC();
			this.loadData();
		},
		methods: {
			loadData(page = 1, per_page = 5){
				axios(`/api/users?page=${page}&per_page=${per_page}`).then((result)=>{
					this.user_list = result.data.users.data;
					this.paginate = result.data.paginate;
					closeLoading();
				}).catch((ex)=>{
					//closeLoading();
					//showAlertMsg("Error al cargar la infomacion",operacion.DEFAULT,operacionStatus.FAIL)
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