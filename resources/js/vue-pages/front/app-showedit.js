
Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);
Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);

import {formatter88,formatter87} from '../../formatters';
import {getPostEvent} from '../../service';
import PostEventCreate from '../../components/post/PostEventCreateComponent.vue';
import PostEventShowComponent from '../../components/post/PostEventShowComponent.vue';

if(document.getElementById('appEditPostFront') != undefined){
    const appEditPostFront = new Vue({
        el: "#appEditPostFront",
        components: {
            "postevent-create": PostEventCreate,
        },
        data: {
            isLoading:false, //for loading info post
            acAppData: window.obj_ac_app,
            modelo_create: [],
        },
        mounted: function(){
            this.target_id = parseInt(document.getElementById("idpostevent").value);
            if(!isNaN(this.target_id)){
                this.loadData();
            }            
        },
        methods: {
            loadData: function(){
                this.isLoading = true;
                getPostEvent(this.target_id).then(result => {
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }  
    
                    this.modelo_create.push(formatter88(response.data,this.acAppData.storage_url));
                }).catch(ex => {
                    const target_process = "Recuperar elemento";
                    StatusHandler.Exception(target_process,ex);    
                }).finally(e => {
                    this.isLoading = false;
                });
            },
            postEventCreated: function(e){
                window.location.href =  this.acAppData.base_url + `/postshow/${e.id}`;
            }            
        }
    });
}

//Show
if(document.getElementById('appShowPostFront') != undefined){
    const appShowPostFront = new Vue({
        el: "#appShowPostFront",
        components: {
            "postevent-show": PostEventShowComponent
        },
        data: {
            isLoading: false,
            acAppData: window.obj_ac_app,
            target_id: null,
            items_postevents: [],        
        },
        mounted: function(){
            this.target_id = parseInt(document.getElementById("idpostevent").value);
            if(!isNaN(this.target_id)){
                this.loadData();
            }                 
        },
        methods: {
            loadData: function(){
                this.isLoading = true;
                getPostEvent(this.target_id).then(result => {
                    let response = result.data;
                    if(response.code == 0){ //sino existe lo detiene aqui 
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }  

                    this.items_postevents.push(formatter88(response.data,this.acAppData.storage_url));
                }).catch(ex => {
                    let target_process = "Recuperar elemento";
                    StatusHandler.Exception(target_process,ex);   
                }).finally(e => {
                    this.isLoading = false;
                });

            },
            onUpdatePostEvent: function(){

            },
            onDeletePost: function(){

            },
            onSources: function(sources){
                var items = sources.map((e)=>{{
                    return formatter87(e,3);
                }});

                this.$refs.mediaviewer.builderAndShow(items,'POST_EVENTS',items[0]);
            }
        }
    });
}



