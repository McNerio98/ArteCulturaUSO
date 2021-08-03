
//import StatusHandler from "../sw-status"

//Se van para mi contenido 
Vue.component('post-event', require('../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../components/post/ModalVideo.vue').default);

// Este se va para eventos 
Vue.component('post-preview-mini-desing',require('../components/post/PostEventPreviewRow.vue').default);

//Se queda 
Vue.component('preview-media',require('../components/media/PreviewMediaComponent.vue').default);
Vue.component('post-general',require('../components/post/PostGeneralComponent.vue').default);
Vue.component('media-viewer', require('../components/media/ViewMediaComponent.vue').default);
Vue.component('summary-item',require('../components/post/PostEventComponent.vue').default);

const STATE_SEARCH= {
    DEFAULT: 1,
    OK: 2,
    VOID: 3
};

const appHome = new Vue({
    el: '#appHome',
    data: {          
        //Start key for pagination
        approval_items: [],
        globalPage: 1,
        paginate_approval: {
            'total': 0,
            'current_page':0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0				
        },
        //End key for pagination 
        spinner_approval: false,
        postevent_selected: null,
        media_view: {
            owner: 0,
            target: {},
            items: []
        },
        notifiers_data: {
            posts: -1,
            events: -1,
            requests: -1,
            users: -1
        },
        panel1_index: 1,  // 1-options |  2- search | 3-mini-view | 4-create nuew post
        shearch_panel1_state: 1,
        desc_to_search: "",
        result_post_search: [],
        id_node_selectd: 0,        
        popular_post: [],
        node_child_selected : null,
        preview_mini_selected: null,
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
        this.approval();
        this.notifiers();
    },
    mounted: function(){
        //
    },
    methods: {
        /*Method for Pagination */
        changePage(pg){
            this.globalPage = pg;
            this.paginate_approval.current_page = this.globalPage;
            this.approval(pg,15);
        },        
        /*End Method for Pagination */
        notifiers: function(){
            axios(`/notifiers`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }                
                this.notifiers_data = response.data;
            }).catch(ex =>{
                StatusHandler.Exception("Recuperar PostEvent ", ex);
            });
        },
        onSources: function(sources){
            //Formateando segun el formato esperado por el preview 
            var aux = sources.map((e)=>{
                return {
                    id: e.id,
                    type: e.type_file,
                    url: e.name,
                    owner_id: 0,
                }
            });

            this.media_view.items = aux;
            this.media_view.target = aux[0];
            $('#modaPreviewMedia').modal('show');            
        },
        getApprovalEl: function(emit_data){
            this.spinner_approval  = true;
            axios(`/api/post/${emit_data.id}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }

                this.spinner_approval = false;
                var e = response.data;
                console.log("Este es response");
                console.log(e);
                var current = {
                    post: {
                        id: e.id,
                        title: e.title,
                        description: e.content,
                        type: e.type_post,
                        is_popular: false,
                        status: 'review',
                        created_at: e.created_at,
                    },
                    creator: {
                        id: e.creator_id,
                        name: e.creator_name,
                        nickname: e.creator_nickname,
                        profile_img:  window.obj_ac_app.base_url + "/files/profiles/" + e.creator_profile, 
                    },
                    media: e.media.map(ng => {//el formato para esto se filtra en el otro compnente
                        switch(ng.type_file){
                            case "image": {ng.name = window.obj_ac_app.base_url +"/files/images/"  + ng.name;break;}
                            case "docfile": {ng.url = window.obj_ac_app.base_url + "/files/pdfs/" + ng.name;break;}
                            case "video": {ng.name_temp = window.obj_ac_app.base_url + "/images/youtube_item.jpg";break;}
                        }
                        return ng;
                    }),
                    meta: e.meta
                }

                this.postevent_selected = current;
            }).catch(ex=>{
                StatusHandler.Exception("Recuperar PostEvent ", ex);
            }).finally(e => {
                this.spinner_approval = false;
            });
        },
        approval: function(page = 1, per_page = 15){            
            this.spinner_approval = true;
            axios(`/approval?page=${page}&per_page=${per_page}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.approval_items = response.data.map(e => {
                    return {
                        id: e.id, 
                        title: e.title,
                        description: e.description,
                        type: e.type,
                        presentation_img: e.presentation_img != undefined ? window.obj_ac_app.base_url + "/files/images/"+e.presentation_img : null,
                        is_popular: e.is_popular,
                        dtl_event: {
                            event_date: e.event_date, //convertir a letras 
                            has_cost: e.has_cost,
                            cost: e.cost,
                            frequency: e.frequency
                        },
                        creator: {
                            id: e.creator_id,
                            nickname: e.nickname
                        }
                    }
                });
                this.paginate_approval = response.paginate;                
            }).catch(ex=>{
                let name_process = "Recuperar elementos en aprobación";
                StatusHandler.Exception(name_process,ex);
            }).finally(e=>{
                this.spinner_approval = false;
            });
        },
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
    },
    computed: {
        //for pagination server side 
        isActive(){
            return this.paginate_approval.current_page;
        },	
        pagesNumber(){
            if(!this.paginate_approval.to){return [];}

            var from = this.paginate_approval.current_page - 2; //TODO offset
            if(from < 1){from = 1;}

            var to = from + (2 + 2); //TODO
            if(to >= this.paginate_approval.last_page){
                to = this.paginate_approval.last_page;
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
