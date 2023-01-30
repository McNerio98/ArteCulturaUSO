
/**
 * Dev: mario.nerio
 * Content file: list all, new, update memory item 
 */



Vue.component('memory-create',require('../../components/memories/MemoryCreateComponent').default);
Vue.component('media-viewer', require('@/components/media/ViewMediaComponent.vue').default);
Vue.component('control-trim', require('../../components/trim/TrimComponentv2.vue').default);
Vue.component('spinner1',require('@/components/spinners/Spinner1Component.vue').default);

import {formatter89,formatter87} from '@/formatters';
import {getMemory,getAdminMemories} from '@/service';
import Memory from '../../components/memories/MemoryShowComponent.vue';
import MemorySummary from '@/components/memories/MemoryMiniViewComponent.vue';
import NoDataFound from '@/components/NoDataFound.vue';
import {getABC} from '@/utils.js';
import PaginationComponent from '@/components/pagination/PaginationComponent.vue';

//Index,  all items (index)
if(document.getElementById("appMemories") != undefined){
    const appMemories = new Vue({
        components: {
            'memory-summary': MemorySummary,
            'no-records-found' : NoDataFound,
            'pagination' : PaginationComponent
        },
        el: "#appMemories",
        data: {
            acAppData: {},
            items: [],
            isGettingResources: false,
            /**---------------------------------- */
            ABC: [],
            filterSelected: null,
            routeDynamic: null,
            componentPagKey: 100,
            showPagination: true,          
            /**---------------------------------- */   
        },
        created: function(){
            this.acAppData = window.obj_ac_app;
        },
        mounted: function(){
            this.ABC = getABC();            
            this.filterSelected = "ALL";
            this.getData();            
            //this.loadData();
        },
        methods: {
            getData: function(){
                if(this.filterSelected == null){return;}
                this.isGettingResources = true;
                this.showPagination = true;
                this.routeDynamic = getAdminMemories(this.filterSelected);
                this.componentPagKey += 1;
            },            
            onReadMemory: function(id){
                window.location.href = this.acAppData.base_url + "/admin/memories/show/"+id
            },
            onLoadData: function(dataPag){
                this.showPagination = (dataPag.length > 0); 
                this.items = dataPag.map(e => {
                    e.media = [];
                    return formatter89(e,this.acAppData.storage_url);
                });               
                //Despues de recuperar, remover el loading
                this.isGettingResources = false;                                               
            },            
            onSelectFilter: function(selected){
                if(this.isGettingResources){return;} //Si esta obteniendo esperar 
                this.filterSelected = selected;
                this.getData();
            }            
        }
    });
}

//Show item (show)
if(document.getElementById("appMemoryShow") != undefined){
    const appMemoryShow = new Vue({
        el: "#appMemoryShow",
        components: {Memory},
        data: {
            acAppData: {},
            idmemory: 0,
            modelo: [],
            flags: {
                itemNotExist: false
            }
        },
        created: function(){
            this.idmemory = isNaN(parseInt($("#idmemory").val())) ? 0 : parseInt($("#idmemory").val());
        },
        mounted: function(){
            this.acAppData = window.obj_ac_app;
            this.getData();
        },
        methods: {
            getData: function(){
                if(this.idmemory == 0){
                    window.location.href = this.acAppData.base_url  + "/admin/memories";
                    return;
                }

                getMemory(this.idmemory).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }
                    this.modelo.push(formatter89(response.data,this.acAppData.storage_url));
                }).catch(ex=>{
                    let target_process = "Recuperar elemento especificado"; 
                    StatusHandler.Exception(target_process,ex);
                });            
            },

            onSources: function(object_media){
                const items =  object_media.items.map((e)=>{{
                    return formatter87(e,0);
                }});
                const target = formatter87(object_media.target,0);
                this.$refs.mediaviewer.builderAndShow(items,'MEMORIES',target);         
            },

            onDeletedMemory: function($id){
                window.location.href = this.acAppData.base_url + "/admin/memories";
            },
            onEditMemory: function($id){
                window.location.href = this.acAppData.base_url + "/admin/memories/create?idm="+$id;
            },
            onPromo: function(id){
                window.location.href = this.acAppData.base_url + `/admin/promociones/create?tarid=${id}&tartype=memory`;
            }                 
        }
    });
}

//New item or update  (create or update)
if(document.getElementById("appMemoryCreateUpdate") != undefined){
    //Para el administrador se maneja una sola vista, para homenajes y biografias 
    const appMemoryCreateUpdate = new Vue({
        el: "#appMemoryCreateUpdate", 
        data: {
            idmemory: 0,
            acAppData: {},
            modelo: [],

            trim_buffer: {
                aspec_ratio: 2/3,
                window_open: false,
                target: "" //element that open cropper 
            },

            main_img_buffer: {
                change: false,
                base64:  ""
            },

            text: ""
        },
        created: function(){
            this.idmemory = isNaN(parseInt($("#idmemory").val())) ? 0 : parseInt($("#idmemory").val());
        },
        mounted: function(){
            this.acAppData = window.obj_ac_app;
            if(this.idmemory == 0){
                this.createMemory();
            }else{
                this.getDataMemory();
            }
        },
        methods: {
            createMemory: function(){
                /**
                 * Al Formateador 89 se le debe pasar un registro en formato de tabla memories
                 */
                this.modelo.push(formatter89({
                    id: 0,
                    created_at: null,
                    type: "biography", //biography,memory
                    area: "",
                    name: "",
                    other_name: "",
                    birth_date: new Date("2000-01-02"),
                    death_date: new Date(), 
                    content: "",
                    presentation_img: null,
                    creator_id: 0,
                    status:"approved",//preapproved, approved, decline
                    media: []
                },this.acAppData.storage_url));
            },
            getDataMemory: function(){
                if(this.idmemory == 0){
                    window.location.replace(this.acAppData +"/" + "memories");
                    return;
                }
                //Service
                getMemory(this.idmemory).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }
                    this.modelo.push(formatter89(response.data,this.acAppData.storage_url));
                }).catch(ex=>{
                    let target_process = "Establecer Elemento como destacado"; 
                    StatusHandler.Exception(target_process,ex);
                });                     
            },
            openTrimPrincipalPic: function(file){
                
                this.trim_buffer.target = "MAIN_IMG_MEMOY";
                this.$refs.acVmCompCropper.openTrim(file);
            },
            principalPicCropped: function(base64){
                switch(this.trim_buffer.target){
                    case "MAIN_IMG_MEMOY": {
                        //pass to component 
                        this.$refs.acVmCompMemory[0].setPresentationImg(base64);
                        break;
                    }
                }
                //pass chioldren component 
                this.trim_buffer.target = "";
            }
        }
    });
}
