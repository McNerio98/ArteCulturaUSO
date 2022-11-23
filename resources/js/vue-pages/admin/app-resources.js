
import ResouceCreate from '@/components/recursos/ResourceCreateComponent.vue';
import ResouceSummary from '@/components/recursos/ResourceShowCardComponent.vue';
import ResouceShow from '@/components/recursos/ResourceShowComponent.vue';
import {getModel91,formatter91,formatter87} from '@/formatters';
import Trimmer from '@/components/trim/TrimComponentv2.vue';
import { getAllResources,getResource } from '@/service';
import NoDataRegister from '@/components/NoDataRegister.vue';

Vue.component('media-viewer', require('@/components/media/ViewMediaComponent.vue').default);

// index
if(document.getElementById("appResourcesAdminIndex") != undefined){
    const appResourcesAdminIndex = new Vue({
        el: "#appResourcesAdminIndex",
        components: {
            'resouce-summary' : ResouceSummary,
            'no-records' : NoDataRegister            
        },
        data: {
            acAppData: window.obj_ac_app,
            items: []
        },
        created: function(){
            this.getDataResources();
        },
        methods: {
            getDataResources: function(){
                getAllResources().then(result =>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }        
                    
                    this.items = response.data.map(e => {
                        e.media = []; //no lo trae por temas de carga y que no lo necesita en apartado de mostrar todos
                        return formatter91(e,this.acAppData.storage_url)
                    });                    
    
                }).catch(ex => {
                    const target_process = "Recuperar elementos"; 
                    StatusHandler.Exception(target_process,ex);
                })
            },
            onReadResource: function(id){
                window.location.replace(this.acAppData.base_url + '/admin/recurso/' + id);
            }            
        }
    });
}

// create, edit 
if(document.getElementById("appResourcesAdminCreate") != undefined){
    const appResourcesAdminCreate = new Vue({
        el: "#appResourcesAdminCreate",
        components: {
            'resource-create' : ResouceCreate,
            'control-trim' : Trimmer
        },        
        data: {
            idresource: 0,
            modelo: [],
            acAppData: window.obj_ac_app,
            trim_buffer: {
                aspec_ratio: 2/3,
                window_open: false,
                target: "" //element that open cropper 
            },            
        },
        mounted: function(){
            this.idresource = isNaN(parseInt($("#idresource").val())) ? 0 : parseInt($("#idresource").val());
            if(this.idresource === 0){
                this.createResource();
            }else{
                this.getDataResource();
            }
        },
        methods: {
            createResource: function(){
                this.modelo.push(formatter91(getModel91(),this.acAppData.storage_url));
            },            
            openTrimPrincipalPic: function(file){
                this.$refs.acVmCompCropper.openTrim(file);
                this.trim_buffer.target = "MAIN_IMG_RESOURCE";
            },            
            principalPicCropped: function(base64){
                switch(this.trim_buffer.target){
                    case "MAIN_IMG_RESOURCE": {
                        //pass to component 
                        this.$refs.acVmCompResource[0].setPresentationImg(base64);
                        break;
                    }
                }
                //pass chioldren component 
                this.trim_buffer.target = "";
            },
            getDataResource: function(){
                getResource(this.idresource).then(result => {
                    const response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }
                    this.modelo.push(formatter91(response.data,this.acAppData.base_url));
                }).catch(ex => {
                    let target_process = "Recuperar elemento especificado"; 
                    StatusHandler.Exception(target_process,ex);
                });
            },
            onCreateResource: function(id){
                window.location.replace(this.acAppData.base_url + "/admin/recursos/"+id);
            }
        }
    });
}
//show 
if(document.getElementById("appResourcesAdminShow") != undefined){
    const appResourcesAdminShow = new Vue({
        el: "#appResourcesAdminShow",
        components: {
            'resource': ResouceShow
        },
        data: {
            idresource: 0,
            modelo: [],
            acAppData: window.obj_ac_app
        },
        created: function(){
            this.idresource = isNaN(parseInt($("#idresource").val())) ? 0 : parseInt($("#idresource").val());
        },        
        mounted: function(){
            this.getData();
        },
        methods: {
            getData: function(){
                if(this.idresource == 0){
                    window.location.replace(this.acAppData.base_url  + "/admin/recursos");
                    return;
                }         
                
                getResource(this.idresource).then(result => {
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }
                    this.modelo.push(formatter91(response.data,this.acAppData.storage_url));                    
                }).catch(ex => {
                    let target_process = "Recuperar elemento especificado"; 
                    StatusHandler.Exception(target_process,ex);                
                });
            },
            onSources: function(object_media){
                const items =  object_media.items.map((e)=>{{
                    return formatter87(e,0);
                }});
                const target = formatter87(object_media.target,0);
                this.$refs.mediaviewer.builderAndShow(items,'RESOURCES',target);         
            },            
            onEditResource: function(id){
                window.location.href = this.acAppData.base_url + "/admin/recursos/create?idr="+id;
            },
            onDeletedResource: function(id){
                window.location.href = this.acAppData.base_url + "/admin/recursos";
            },
            onPromo: function(id){
                window.location.href = this.acAppData.base_url + `/admin/promociones/create?tarid=${id}&tartype=resource`;
            }                        
        }
    });
}
