Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('summary-item',require('../../components/post/PostEventComponent.vue').default);
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);

const appEvents = new Vue({
    el: "#app_events",
    data: {
        flags: {
            has_paged1: false,//SI SE TIENE LA PAGINACION 1, (EVENTOS)
        },
        spinners: {
            S1: false
        },
        events: [],
        postevent_selected: undefined,
        acAppData: {}
    }, 
    mounted: function(){
        this.acAppData = window.obj_ac_app;
        this.loadEvents();
        this.checkParam();
    },
    methods: {
        checkParam: function(){
            var target_item = parseInt($("#targetOpenItem").val());
            target_item =  isNaN(target_item) ? 0 : target_item;
            if(target_item != 0){
                this.loadTarget(target_item);
            }
        },
        loadTarget: function(item_id){
            axios(`/postevent/${item_id}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }          

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
            }).catch(ex=>{
                StatusHandler.Exception("Recuperar item",ex);
            })
            
        },
        onSources: function(){

        },
        onClickEvent: function(event_el){
            this.loadTarget(event_el.id);
        },
        loadEvents: function(page = 1){
            var data = {
                page: page,
                init_pagination: ! this.flags.has_paged1 ? true: false //sino se tiene la paginacion se pide una vez 
            }

            this.spinners.S1 = true;
            axios(`/api/posts/events`,{params: data}).then(result =>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  

                //Formating 
                this.events = response.data.map(e => {
                    let ret = {
                        id: e.id, 
                        title: e.title,
                        description: e.description,
                        type: e.type,
                        presentation_img: (e.presentation_img != undefined && e.presentation_type != "video") ? window.obj_ac_app.base_url + "/files/images/"+e.presentation_img : null,
                        presentation_type: e.presentation_type,
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

                    if(ret.presentation_type == "video"){
                        ret.presentation_img = window.obj_ac_app.base_url +"/images/youtube_item.jpg";
                    }
                    return ret;                         
                });
                //Se establece que ya se paginado, si se envio init_pagination = true
                if(! this.flags.has_paged1){this.flags.has_paged1 = true}
            }).catch(ex=>{
                let target_process = "Recuperar eventos cercanos";
                StatusHandler.Exception(target_process,ex);                
            }).finally(e=>{
                this.spinners.S1 = false;
            })
        }
    }
});