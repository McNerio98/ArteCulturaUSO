/**GLOBALS */
Vue.component('media-viewer', require('@/components/media/ViewMediaComponent.vue').default);

import MemoryShow from '@/components/memories/MemoryShowComponent.vue';
import MemorySummary from '@/components/memories/MemoryMiniViewComponent.vue';
import {getMemory,getAllMemories} from '@/service';
import {formatter89,formatter87} from '@/formatters';
import NoDataRegister from '@/components/NoDataRegister.vue';
import {getABC} from '@/utils.js';
import PaginationComponent from '@/components/pagination/PaginationComponent.vue';

//Index
if(document.getElementById("appMemoryIndex") != undefined){
    const appMemoryIndex = new Vue({
        el: "#appMemoryIndex",
        components: {
            'memory-summary' : MemorySummary,
            'no-records' : NoDataRegister,
            'pagination' : PaginationComponent
        },
        data: {
            acAppData: window.obj_ac_app,
            items: [],            
            ABC: [],
            filterSelected: null,
            routeDynamic: null,
            componentPagKey: 100,
            showPagination: true, 
        },
        mounted: function(){
            this.ABC = getABC();            
            this.filterSelected = "ALL";
            this.getData();
        },
        methods: {
            getData: function(){
                if(this.filterSelected == null){return;}
                this.showPagination = true;
                this.routeDynamic = getAllMemories(this.filterSelected);
                this.componentPagKey += 1;
            },
            onReadMemory: function(id){
                window.location.replace(this.acAppData.base_url + "/site/biografias/"+id);
            },
            onLoadData: function(dataPag){
                this.showPagination = (dataPag.length > 0); 
                this.items = dataPag.map(e => {
                    e.media = [];
                    return formatter89(e,this.acAppData.storage_url);
                });                
            },
            onSelectFilter: function(selected){
                this.filterSelected = selected;
                this.getData();
            }
        }
    });
}

//Show
if(document.getElementById("appMemoryShow") != undefined){
    const appMemoryShow = new Vue({
        el: "#appMemoryShow",
        components: {
            'memory' : MemoryShow
        },
        data: {
            idmemory: 0,
            acAppData: window.obj_ac_app,
            modelo: [],
        },
        created: function(){
            this.idmemory = isNaN(parseInt($("#idmemory").val())) ? 0 : parseInt($("#idmemory").val());
            this.getData();
        },
        methods: {
            getData: function(){
                if(this.idmemory == 0){
                    window.location.replace(this.acAppData.base_url  + "/site/biografias");
                    return;
                }                
                getMemory(this.idmemory).then(result=>{
                    const response = result.data;
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
            }
        }
    });
}

