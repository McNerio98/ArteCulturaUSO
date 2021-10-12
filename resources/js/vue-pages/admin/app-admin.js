
//Se queda 
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);
Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
Vue.component('summary-item',require('../../components/post/PostEventComponent.vue').default);

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
        acAppData: {},
        spinners: {
            S1: true
        },
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
    },
    created: function(){
        this.approval();
        this.notifiers();
    },
    mounted: function(){
        this.acAppData = window.obj_ac_app;
    },
    methods: {
        /*Method for Pagination */
        changePage(pg){
            this.globalPage = pg;
            this.paginate_approval.current_page = this.globalPage;
            this.approval(pg);
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
                    name: e.name,
                    url: e.url,
                    owner: {
                        id: e.owner
                    }
                }
            });

            this.media_view.items = aux;
            this.media_view.target = aux[0];
            $('#modaPreviewMedia').modal('show');            
        },
        getApprovalEl: function(emit_data){
            this.spinners.S1  = true;
            axios(`/postevent/${emit_data.id}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }

                this.spinners.S1 = false;
                var e = response.data;
                var current = {
                    post: {
                        id: e.id,
                        title: e.title,
                        description: e.content,
                        type: e.type_post,
                        is_popular: e.is_popular,
                        status: e.status,
                        created_at: e.created_at,
                    },
                    dtl_event: {
                        event_date: e.event_date,
                        has_cost: e.has_cost,
                        cost: e.cost,
                        frequency: e.frequency
                    },                        
                    creator: {
                        id: e.creator_id,
                        name: e.creator_name,
                        nickname: e.creator_nickname,
                        profile_img:  window.obj_ac_app.storage_url + "/files/profiles/" + e.creator_profile, 
                    },
                    media: e.media.map(ng => {//el formato para esto se filtra en el otro compnente
                        switch(ng.type_file){
                            case "image": {ng.url = this.acAppData.storage_url +"/files/images/"  + ng.name;break;}
                            case "docfile": {ng.url = this.acAppData.storage_url + "/files/pdfs/" + ng.name;break;}
                            case "video": {ng.url = this.acAppData.storage_url + "/images/youtube_item.jpg";break;}
                        }
                        ng.owner = e.creator_id;
                        return ng;
                    }),
                    meta: e.meta
                }

                this.postevent_selected = current;
                document.getElementById("ctrlAnchor1").click();
            }).catch(ex=>{
                StatusHandler.Exception("Recuperar PostEvent ", ex);
            }).finally(e => {
                this.spinners.S1 = false;
            });
        },
        approval: function(page = 1, per_page = 15){            
            this.spinners.S1 = true;
            axios(`/approval?page=${page}&per_page=${per_page}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.approval_items = response.data.map(e => {
                    let ret = {
                        id: e.id, 
                        title: e.title,
                        description: e.description,
                        type: e.type,
                        presentation_img: (e.presentation_img != undefined && e.presentation_type != "video") ? this.acAppData.storage_url + "/files/images/"+e.presentation_img : null,
                        presentation_type: e.presentation_type,
                        is_popular: e.is_popular,
                        status: e.status,
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
                    if(ret.presentation_type == "video"){
                        ret.presentation_img = window.obj_ac_app.base_url +"/images/youtube_item.jpg";
                    }
                    return ret;
                });
                this.paginate_approval = response.paginate;                
            }).catch(ex=>{
                let name_process = "Recuperar elementos en aprobación";
                StatusHandler.Exception(name_process,ex);
            }).finally(e=>{
                this.spinners.S1 = false;
            });
        },

        changePanel1: function(new_index){
            if(this.panel1_index === 4){
                this.panel1_index = 1;
                return;
            }
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
