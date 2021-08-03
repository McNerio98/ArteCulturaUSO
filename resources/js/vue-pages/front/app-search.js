
//HERE COMPONENTS 

Vue.component('profile', require('../../components/users/SearchProfile.vue').default);
Vue.component('search-component', require('../../components/search/SearchComponent.vue').default);
Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);

const appSearch = new Vue({
    el: '#app-search',
    data: {
        finded: {},
        has_paginate1: false, //For profiles 
        has_paginate2: false, //For post events 
        valid_params: false,
        spinners: {
            S1: false,
        },
        params: {},
        profiles: [],
        items: [],
        //For pagination profiles 
        globalPage1: 1,
        pagination1: {
            'total': 0,
            'current_page':0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0				
        }                 
    },
    created: function(){
        this.params = window.params_search;
    },
    mounted: function(){
        //StatusHandler.ShowStatus("Inconsistencia datos, recargue el sitio ",StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
    },
    methods: {
        exeSeach: function(ng){
            if(ng.label == undefined){
                alert("Error, variables no definidas");
                return;
            }

            if(ng.type_search != "cat" && ng.type_search != "tag" && ng.type_search != "default"){
                alert("Inconsistencia de datos");
                return;
            }

            this.finded.id_filter = ng.id_filter;
            this.finded.label = ng.label;
            this.finded.type_search = ng.type_search;
            this.has_paginate1 = false; //es una nueva busqueda 

            this.loadDataProfiles();
        },
        loadDataProfiles: function(page = 1){
            //El per page se maneja desde el controlador 
            if(this.finded.type_search != "cat" && this.finded.type_search != "tag" && this.finded.type_search != "default"){
                StatusHandler.ValidationMsg("Inconsistencia de datos, recargue el sitio");
                return;
            }

            var data = {
                page: page,
                id_filter: this.finded.id_filter,
                label: this.finded.label,
                type_search: this.finded.type_search,
                init_paginate: ! this.has_paginate1 ? true: false //sino se tiene la paginacion se pide 
            }

            this.spinners.S1 = true;
             //If  you have to pass some params along with GET request, you need to use params property of config object
            axios(`/api/exeSearch`,{params: data}).then(result=>{
                var response = result.data;
                var temp = response.data.map(ax =>{
                    ax.path_media = window.obj_ac_app.base_url + "/files/profiles/" + ax.path_media;
                    return ax;
                });

                this.profiles = temp;
                this.pagination1 = response.pagination;
                if(! this.has_paginate1){this.has_paginate1 = true;}//si ya tengo la paginacion ya no se pide 
            }).catch(ex=>{

            }).finally(el =>{
                this.spinners.S1 = false;
            });

        },
        //Method for pagination 1 (Profiles)
        changePage1: function(pg){
            this.globalPage1 = pg;
            this.pagination1.current_page = this.globalPage1;
            this.loadData(pg);
        }
    },
    computed: {
        //Computed for pagination (1) Profiles
        isActive1(){
            return this.pagination1.current_page;
        },	
        pagesNumber1(){
            if(!this.pagination1.to){return [];}

            var from = this.pagination1.current_page - 2; //TODO offset
            if(from < 1){from = 1;}

            var to = from + (2 + 2); //TODO
            if(to >= this.pagination1.last_page){
                to = this.pagination1.last_page;
            }

            var pagesArray = [];
            while(from <= to){
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }          
    }
});

