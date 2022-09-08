Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

import {formatter88} from '../../formatters';
import {getPostEvent} from '../../service';
import PostEventCreate from '../../components/post/PostEventCreateComponent.vue';
import PostEventShowComponent from '../../components/post/PostEventShowComponent.vue';

//Edit PostEvent Item 
if(document.getElementById("appAdminUpdatePost") != undefined){
    const appAdminUpdatePost = new Vue({
        el: "#appAdminUpdatePost",
        components:{
            "postevent-create": PostEventCreate,
        },    
        data: {
            isLoading:false, //for loading info post
            target_id: null,
            acAppData: {},
            modelo_create: [],
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
                this.isLoading = true;
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
                    this.isLoading = false;
                })
            },
            postEventCreated: function(e){
                window.location.href =  this.acAppData.base_url + "/admin/post/show/" + e.id;
            }
        }
    
    });
}

//Show PostEvent Imte 
if(document.getElementById("appAdminShowPost") != undefined){
    const appAdminShowPost = new Vue({
        el: "#appAdminShowPost",
        components: {
            "postevent-show": PostEventShowComponent
        },
        data: {
            isLoading: false,
            acAppData: {},
            target_id: 0,
            items_postevents: []
        },
        created: function(){
            this.acAppData = window.obj_ac_app;
            this.target_id = parseInt(document.getElementById("temp_iden_edit").value);
            if(!isNaN(this.target_id)){
                this.loadData();
            }            
        },
        methods: {
            loadData: function(){
                this.isLoading = true;
                getPostEvent(this.target_id).then(result =>{
                    let response = result.data;
                    if(response.code == 0){ //sino existe lo detiene aqui 
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }  

                    this.items_postevents.push(formatter88(response.data,this.acAppData.storage_url));
                }).catch(ex =>{
                    let target_process = "Recuperar elemento";
                    StatusHandler.Exception(target_process,ex);       
                }).finally(e =>{
                    this.isLoading  = false;
                })                
            },
            onUpdatePostEvent: function(){

            },
            onDeletePost: function(post_id){
                window.location.href = 
                this.acAppData.base_url + "/admin/content";
            },
            onSources: function(){

            }
        }

    });
}
