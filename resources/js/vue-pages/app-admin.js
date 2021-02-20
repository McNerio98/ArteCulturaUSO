
//import StatusHandler from "../sw-status"

Vue.component('post-event', require('../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../components/post/ModalVideo.vue').default);

Vue.component('post-preview-mini-desing',require('../components/post/PostEventPreviewRow.vue').default);

Vue.component('post-general',require('../components/post/PostGeneralComponent.vue').default);

const STATE_SEARCH= {
    DEFAULT: 1,
    OK: 2,
    VOID: 3
};

const appHome = new Vue({
    el: '#appHome',
    data: {          
        token_acces: null,
        panel1_index: 1,  // 1-options |  2- search | 3-mini-view | 4-create nuew post
        shearch_panel1_state: 1,
        desc_to_search: "",
        result_post_search: [],
        id_node_selectd: 0,        
        popular_post: [],
        node_child_selected : null,
        preview_mini_selected: null,
        notificadores: {
            posts: 0,
            events: 0,
            post_revision: 0,
            users: 0
        },
        post_to_create: "post",

        post_selected: {
            post: {
                id: 0,
                title: "",
                description: "",
                type: "post",
                creator_id: 0,
                is_popular: false,
                status: "approved",
                created_at: "",
                name: "",
                artistic_name: ""                    
            },
            media: [],
            meta: []
        }

    },
    created: function(){
        this.saveTokenStorage();
        this.loadPopularPost();
    },
    mounted: function(){
        //
    },
    methods: {
        loadPostById: function(id_post){
            console.log("Voy a cargar info del nuevo post creado con este id " + id_post);
            axios(`/api/post/find/${id_post}`).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.post_selected = response.data;
                console.log(this.post_selected);
                this.panel1_index = 3;
            }).catch((ex)=>{
                StatusHandler.Exception("Recuperar Post",ex);
            });
        },
        saveTokenStorage: function(){
            this.token_acces = $("#current_save_token_generate").val();
            window.localStorage.setItem("cursave_token_gnt",this.token_acces);
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token_acces}`; //todas las solicitudes lo llevaran  
        },
        loadPopularPost: function(){
            //Leyendo elementos destacados/populares
            const params = {
                desc: "", //esto lo reconoce como indefinido laravel en el controller
                popular: true
            };
            axios.post(`/api/post/findPostsPopular`,params).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.popular_post = response.data;
            }).catch((error)=>{
                let target_process = "Recuperar post populares"; 
                let msg = "El proceso ("+target_process+")no se ha podido completar, póngase con soporte técnico."
                StatusHandler.ShowStatus(msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                console.error(error);
            });
        },
        changePanel1: function(new_index){
            if(this.panel1_index === 4){
                this.panel1_index = 1;
                return;
            }
            this.panel1_index = new_index;
        },
        createNewPostEvent: function(type_element,new_index){
            this.post_to_create = type_element === "event"?"event":"post";
            this.changePanel1(new_index);
        },
        runFindPostEvent: function(){
            //Validaciones 
            if(this.desc_to_search.length < 2){
                StatusHandler.ShowStatus("Escriba al menos dos caracteres para realizar la busqueda",StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                return;
            }
            const params = {
                desc: this.desc_to_search,
                popular: false
            };
            axios.post('/api/post/findPostsPopular',params).then((result)=>{
                let response = result.data;

                if(response.code == 0){
                    console.log(response.msg);
                    return;
                }
                this.result_post_search = response.data;
                this.shearch_panel1_state = 2;
            }).catch((error)=>{
                let target_process = "Busqueda post populares"; 
                let msg = "El proceso ("+target_process+")no se ha podido completar, póngase con soporte técnico."
                StatusHandler.ShowStatus(msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                console.error(error);
            });
        },
        ShowPanelPostData: function(unit){
            this.preview_mini_selected = unit;
            axios(`/api/post/find/${unit.id}`).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.post_selected = response.data;
                console.log(this.post_selected);
                this.panel1_index = 3;
            }).catch((error)=>{
                let target_process = "Recuperar Post"; 
                let msg = "El proceso ("+target_process+")no se ha podido completar, póngase con soporte técnico."
                StatusHandler.ShowStatus(msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                console.error(error);
            });
        },
        setPostPopular: function(state){
            //state = {id,state}
            this.preview_mini_selected.is_popular = state.is_popular;
            if(state.is_popular){
                this.popular_post.push(this.preview_mini_selected);
            }else{
                this.removeViewPopular(this.preview_mini_selected.id);
            }
        },
        removeViewPopular: function(id_elemento){
            let index = -1;
            for(e in this.popular_post){
                if(this.popular_post[e].id == id_elemento){
                    index = e;
                    break;
                }
            }
            if(index > -1){
                this.popular_post.splice(index,1);
            }
        }
    }
});
