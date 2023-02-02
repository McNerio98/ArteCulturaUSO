Vue.component('contact', require('@/components/users/Contacto.vue').default);
Vue.component('pnl-pagination',require('@/components/users/ContainerPagination.vue').default);

const appUsersAdminVue = new Vue({
    el: '#appUsersAdmin',
    data: function(){
        return {         
            showPagination: true,
            filters: {
                users: ""
            },
            pagination: {
                'total': 0,
                'current_page':0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0				
            },            
            user_list: [],
            acAppData: {}
        }
    },
    mounted: function(){
        this.acAppData = window.obj_ac_app;
        var filter_user = $("#hFilterUser").val().trim();
        var to_filter = filter_user.length == 0 ? 'all' : filter_user;
        this.getByFilter(to_filter);
    },
    methods: {
        getByFilter: function( filter_target){
            var filter_users = ["all","request","disabled","enabled"];
            if(filter_users.indexOf(filter_target) === -1){
                StatusHandler.BadDataMsg("parametro de filtro")
                return;
            }            
            this.filters.users = filter_target;
            this.loadData();
        },
        loadData: function(page = 1, per_page = 15){
            StatusHandler.ShowLoading("Consultando datos");
            axios(`/users?page=${page}&per_page=${per_page}&filter=${this.filters.users}`).then(result =>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.CloseLoading();
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                StatusHandler.CloseLoading();

                this.user_list = response.data.items.map(e=>{
                    e.img_profile = this.acAppData.storage_url + "/files/profiles/" + e.img_profile;
                    return e;
                });

                this.showPagination =  (this.user_list.length > 0);
                this.pagination = response.data.pagination;
            }).catch(ex=>{
                StatusHandler.CloseLoading();
                StatusHandler.Exception("Obtener usuarios ", ex);
            });
        },
        /**Methos for paginations only */
        changePage(pg){
            this.loadData(pg);
        },        
        /**End Methos for paginations only */
    },
    computed: {
        /*Computed for pagination only */
        isActive(){
            return this.pagination.current_page;
        },			
        pagesNumber(){
            if(!this.pagination.to){
                return [];
            }

            var from = this.pagination.current_page - 2; //TODO offset
            if(from < 1){
                from = 1;
            }

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
        /*End Computed for pagination only */        
    }
});