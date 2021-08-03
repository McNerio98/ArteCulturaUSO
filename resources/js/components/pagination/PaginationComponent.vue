<template>
    <nav aria-label="Navegacion elementos en aprobación">
        <ul class="pagination justify-content-center">
            <li v-bind:class="{'disabled' : ! (pagination.current_page > 1)}" class="page-item">
                <a @click.prevent="changePage(pagination.current_page - 1)" class="page-link"
                    href="javascript.void(0);">Anterior</a>
            </li>
            <li v-for="page in pagesNumber" v-bind:key="page" v-bind:class="[page == isActive? 'active':'']"
                class="page-item">
                <a @click.prevent="changePage(page)" class="page-link" href="javascript:void(0);">{{page}}</a>
            </li>
            <li v-bind:class="{'disabled' : ! (this.pagination.current_page < this.pagination.last_page)}" class="page-item">
                <a @click.prevent="changePage(pagination.current_page + 1)" class="page-link"
                    href="javascript:void(0);">Siguiente</a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: {
            route: {type: String,required:true},
            per_page: {type: Number,default: 15}
        },
        data: function(){
            return {
                items: [], 
                globalPage: 1,
                pagination: {
                    'total': 0,
                    'current_page':0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0				
                }                
            }
        },
        mounted() {
           this.loadData();
        },
        methods: {
            loadData:  function(page = 1){
                axios(this.route + `?page=${page}&per_page=${this.per_page}`).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }
                    this.pagination = response.pagination;

                    this.$emit('source-items',response.data);
                }).catch(ex=>{
                    let name_process = "Recuperar ítems en paginación";
                    StatusHandler.Exception(name_process,ex);
                }).finally(e=>{

                });
            },
            changePage(pg){
                this.globalPage = pg;
                this.pagination.current_page = this.globalPage;
                this.loadData(pg);
            }   
        },
        computed: {
            isActive(){
                return this.pagination.current_page;
            },	
            pagesNumber(){
                if(!this.pagination.to){return [];}

                var from = this.pagination.current_page - 2; //TODO offset
                if(from < 1){from = 1;}

                var to = from + (2 + 2); //TODO
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }            
        }
    }
</script>
