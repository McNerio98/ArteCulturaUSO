
import StatusHandler from "../sw-status"

Vue.component('post-component', require('../components/PostComponent.vue').default);
Vue.component('postFormulario-component', require('../components/post/Formulario.vue').default);
Vue.component('postMedia-component', require('../components/post/media.vue').default);
Vue.component('postModal-component', require('../components/post/modal.vue').default);

const STATE_SEARCH= {
    DEFAULT: 1,
    OK: 2,
    VOID: 3
};


Vue.component('post-preview-mini-desing',require('../components/post/PostEventPreviewRow.vue').default);

const appHome = new Vue({
    el: '#appHome',
    data: {
        token_acces: null,
        panel1_index: 1,  // 1-options |  2- search | 3-mini-view | 4-create nuew post
        shearch_panel1_state: 1,
        desc_to_search: "",
        result_post_search: [],
        id_node_selectd: 0,
        node_selected: null,
        popular_post: [],
        node_child_selected : null,
        notificadores: {
            posts: 0,
            events: 0,
            post_revision: 0,
            users: 0
        }
    },
    created: function(){
        this.saveTokenStorage();
        this.load_token_value();
        this.loadPopularPost();
    },
    methods: {
        saveTokenStorage: function(){
            window.localStorage.setItem("cursave_token_gnt",$("#current_save_token_generate").val());
            //window.axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`; //todas las solicitudes lo llevaran  
        },
        load_token_value: function(){
            this.token_acces = window.localStorage.getItem("cursave_token_gnt");
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
            this.panel1_index = new_index;
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
        onShowPanelPostData: function(unit){
            let id_post = unit.id;
            this.node_child_selected = unit;
            axios(`/api/post/find/${id_post}`).then((result)=>{
                let response = result.data;

                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.node_selected = response.data;
                this.panel1_index = 3;
            }).catch((error)=>{
                let target_process = "Recuperar Post"; 
                let msg = "El proceso ("+target_process+")no se ha podido completar, póngase con soporte técnico."
                StatusHandler.ShowStatus(msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                console.error(error);
            });
        },
        setPostPopular: function(id_post){
            //validacion parametros 
            let current_state = this.node_selected.info.is_popular;
            if(!(current_state === 0 || current_state === 1)){  //usar esta validacion
                StatusHandler.ShowStatus("Valores no validos",StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                return;
            }

            let new_state = current_state == 0? 1:0; //switch 

            axios(`/api/post/setPopular/${id_post}/${new_state}`).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.node_selected.info.is_popular = parseInt(response.data.new_state);
                this.node_child_selected.is_popular = parseInt(response.data.new_state);
                
                if(response.data.new_state == true){//se agrega en la lista 
                    this.popular_post.push(this.node_child_selected);
                }else{
                    this.removeViewPopular(this.node_child_selected.id);
                }
            }).catch((error)=>{
                let target_process = "Recuperar Post"; 
                let msg = "El proceso ("+target_process+")no se ha podido completar, póngase con soporte técnico."
                StatusHandler.ShowStatus(msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                console.error(error);
            });
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
