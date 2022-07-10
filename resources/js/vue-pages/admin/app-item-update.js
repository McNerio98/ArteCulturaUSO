Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

import {formatter88} from '../../formatters';
import {getPostEvent} from '../../service';
import PostEventCreate from '../../components/post/PostEventCreateComponent.vue';
import PostEventShowComponent from '../../components/post/PostEventShowComponent.vue';

const appUpdateItem = new Vue({
    el: "#appUpdateItem",
    components:{
        "postevent-create": PostEventCreate,
        "postevent-show": PostEventShowComponent
    },    
    data: {
        acAppData: {},
        flags: {
            load_postevent:false, //for loading info post
            edit_mode: true,
        },
        modelo_create: [],
        items_postevents: [],
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
            this.flags.load_postevent = true;
            getPostEvent(this.target_id).then(result =>{
                let response = result.data;
                if(response.code == 0){ //sino existe lo detiene aqui 
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  

                this.modelo_create.push(formatter88(response.data,this.acAppData.storage_url));
            }).catch(ex =>{
                let target_process = "Recuperar elemento";
                StatusHandler.Exception(target_process,ex);       
            }).finally(e =>{
                this.flags.load_postevent  = false;
            })
        },
        postEventCreated: function(e){
            this.items_postevents.push(formatter88(e,this.acAppData.storage_url));
            this.flags.edit_mode = false;
        },
        onUpdatePostEvent: function(postevent_id){

        },
        onSources: function(){

        }
    }

});