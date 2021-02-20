Vue.component('request-component', require('../../components/requestAccount.vue').default);

const app_inicio = new Vue({
    el: '#app_inicio',
    data: {
        posts_popular: []
    },
    mounted: function(){
        //Cargar post destacados 
        this.cargarPostDestacados();
    },
    methods: {
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
        }
    }
});