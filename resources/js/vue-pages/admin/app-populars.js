

Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
Vue.component('summary-item',require('../../components/post/PostEventComponent.vue').default);
// #Estos dos van unidos 
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);
const appPopulars = new Vue({
    el: "#appPopulars",
    data: {
        items_postevents: [],
        spinners: {
            S1: true,//for load popular content
        },
        postevent_selected: undefined,
        media_view: {
            owner: 0,
            target: {},
            items: []
        },        
    },
    created: function(){
        this.acAppData = window.obj_ac_app;
    },
    mounted: function(){
        this.loadData();
    },
    methods: {
        loadData: function(){
            this.spinners.S1 = true;
            axios(`/api/posts/populars`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                this.items_postevents = response.data.map(e => {
                    let ret = {
                        id: e.id, 
                        title: e.title,
                        description: e.description,
                        type: e.type,
                        presentation_img: (e.presentation_img != undefined && e.presentation_type != "video") ? this.acAppData.storage_url + "/files/images/"+e.presentation_img : null,
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
            }).catch(ex=>{
                let target_process = "Recuperar elementos destacados";
                StatusHandler.Exception(target_process,ex);
            }).finally(e=>{
                this.spinners.S1 = false;
            })
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
        getDataItem: function(emit_data){
            this.spinners.S1  = true;
            axios(`/api/post/${emit_data.id}`).then(result=>{
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
                        is_popular: false,
                        status: 'review',
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
                        profile_img:  window.obj_ac_app.base_url + "/files/profiles/" + e.creator_profile, 
                    },
                    media: e.media.map(ng => {//el formato para esto se filtra en el otro compnente
                        switch(ng.type_file){
                            case "image": {ng.url = this.acAppData.storage_url +"/files/images/"  + ng.name;break;}
                            case "docfile": {ng.url = this.acAppData.storage_url + "/files/docs/pe" + e.id + "/" + ng.name;break;}
                            case "video": {ng.url = this.acAppData.storage_url + "/images/youtube_item.jpg";break;}
                        }
                        ng.owner = e.creator_id;
                        return ng;
                    }),
                    meta: e.meta
                }

                this.postevent_selected = current;
            }).catch(ex=>{
                StatusHandler.Exception("Recuperar elemento", ex);
            }).finally(e => {
                this.spinners.S1 = false;
            });
        }        
    },
});