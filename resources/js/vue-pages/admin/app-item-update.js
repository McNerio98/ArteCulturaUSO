Vue.component('content-create', require('../../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../../components/post/ModalVideo.vue').default);

Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

import {formatter88} from '../../formatters';
import {getPostEvent} from '../../service';

const appUpdateItem = new Vue({
    el: "#appUpdateItem",
    data: {
        acAppData: {},
        spinners: {
            S1:false //for loading info post
        },
        flags: {
            show_edited: false,
        },
        target_id: 0,
        pe_items: [], //post and events 
        buffer: { //buffer para edicion
            edit_mode: false, 
            source: {}
        }
    },
    created: function(){
        this.acAppData = window.obj_ac_app;
    },
    mounted: function(){
        this.target_id = parseInt(document.getElementById("temp_iden_edit").value);
        
        if(!isNaN(this.target_id)){
            this.loadData();
        }
    },
    methods: {
        loadData: function(){
            this.spinners.S1 = true;
            getPostEvent(this.target_id).then(result =>{
                let response = result.data;
                if(response.code == 0){ //sino existe lo detiene aqui 
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  
                
                this.buffer.source=  formatter88(response.data,this.acAppData.storage_url);
                this.buffer.edit_mode = true;
            }).catch(ex =>{
                let target_process = "Recuperar elemento";
                StatusHandler.Exception(target_process,ex);       
            });
        },
        PostEventCreated: function(e){
            var post = {
                post: {
                    id: e.post.id,
                    title: e.post.title,
                    description: e.post.content,
                    type: e.post.type_post,
                    is_popular: false,
                    status: 'review',
                    created_at: e.post.created_at,
                },
                dtl_event: {
                    event_date: e.dtl_event.event_date,
                    has_cost: e.dtl_event.has_cost,
                    cost: e.dtl_event.cost,
                    frequency: e.dtl_event.frequency,
                },                   
                creator: {
                    id: e.creator.id,
                    name: e.creator.name,
                    nickname: e.creator.nickname,
                    profile_img: e.creator.profile_img != undefined ? this.acAppData.storage_url + "/files/profiles/" + e.creator.profile_img.path_file : null, 
                },
                media: e.post.media.map(ng => {//el formato para esto se filtra en el otro compnente
                    switch(ng.type_file){
                        case "image": {ng.url = this.acAppData.storage_url +"/files/images/"  + ng.name;break;}
                        case "docfile": {ng.url = this.acAppData.storage_url + "/files/docs/pe" + e.post.id + "/" + ng.name;break;}
                        case "video": {ng.url = this.acAppData.storage_url + "/images/youtube_item.jpg";break;}
                    }
                    return ng;
                }),
                meta: []                        
            }

            this.pe_items.push(post);
            this.flags.show_edited = true;
        },
        onSources: function(){

        }
    }

});