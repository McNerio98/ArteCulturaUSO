Vue.component('request-component', require('../../components/RequestAccount.vue').default);
Vue.component('search-component', require('../../components/search/SearchComponent.vue').default);

const app_inicio = new Vue({
    el: '#app_inicio',
    data: {
        posts_popular: []
    },
    mounted: function(){
        //Cargar post destacados 
        //this.cargarPostDestacados();
        this.loadAdminData();
    },
    methods: {
        loadAdminData: function(){
            console.log("Cargando la data admin TX");
            //peticion get 
            axios(`/rolesdata`).then(result=>{
                //let response = result.data;
                console.log("TX Este es el resultado");
                console.log(result);
            }).catch(ex=>{
                console.log("TX Este es el error");
                console.log(ex);
                // if(ex.reponse.status == 401){
                //     window.reload();
                // }
            });
            //peticion post 

        },        
        cargarPostDestacados: function(){
            axios(`/api/post/populars`).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                if(response.data.length == 0){ //se crea uno por defecto 
                    let def = {
                        id: 0,
                        title: "",
                        content: "No hay publicaciones o elementos destacados por ahora",
                        type_post: "event",
                        is_popular: true,
                        name: "",
                        path_file: "images",
                        type_file: "/no_image_found.png"
                    };
                    this.posts_popular.push(def);                    
                    return;
                }

                this.posts_popular = response.data;                
            }).catch((ex)=>{
                StatusHandler.Exception("Recuperar post populares",ex);
            });
        },
        exeSeach: function(ng){
            if(ng.id_filter == undefined || ng.label == undefined || ng.type_search == undefined){
                alert("Error");
                return;
            }
            window.location.href = window.obj_ac_app.base_url+`/search?id_filter=${ng.id_filter}&label=${ng.label}&type_search=${ng.type_search}`;
        }
    }
});