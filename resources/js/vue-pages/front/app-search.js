
//HERE COMPONENTS 

Vue.component('profile', require('../../components/users/SearchProfile.vue').default);
Vue.component('search-component', require('../../components/search/SearchComponent.vue').default);
const appSearch = new Vue({
    el: '#app-search',
    data: {
        valid_params: false,
        loading_page: true,
        params: {},
        profiles: [],
        items: []      
    },
    created: function(){
        this.params = window.params_search;
    },
    mounted: function(){
        //StatusHandler.ShowStatus("Inconsistencia datos, recargue el sitio ",StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
    },
    methods: {
        exeSeach: function(ng){
            if(ng.id_filter == undefined || ng.label == undefined || ng.type_search == undefined){
                alert("Error, variables no definidas");
                return;
            }

            if(ng.type_search != undefined){
                if(ng.type_search != "tag" && ng.type_search != "cat"){
                    alert("Inconsistencia de datos");
                    return;
                }
            }
            

            //obtener perfiles 

            //obtener postevents 
            var data = {
                id_filter:  ng.id_filter,
                label: ng.label,
                type_search: ng.type_search
            }

            //va a dependet de la respuesta
            //If  you have to pass some params along with GET request, you need to use params property of config object
            axios(`/api/exeSearch`,{params: data}).then(result=>{
                var response = result.data;
                console.log("Ejecutando busqueda por ajax");
                console.log(response);                
            })

        },
        ByCategory: function(){
            console.log("Realizando peticion");
            axios(`/api/search/byCategory/${this.pattern.cat_id}`).then(result=>{
                let response = result.data;
                this.profiles = response.data;
                this.loading_page = false;
            });
        }
    }
});

