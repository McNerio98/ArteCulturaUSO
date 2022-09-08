
import MemoryShow from '../../components/memories/MemoryShowComponent.vue';
import MemorySummary from '../../components/memories/MemoryMiniViewComponent.vue';
import {getMemory,getAllMemories} from '../../service';
import {formatter89} from '../../formatters';
import NoDataRegister from '../../components/NoDataRegister.vue';
//Index
if(document.getElementById("appMemoryIndex") != undefined){
    const appMemoryIndex = new Vue({
        el: "#appMemoryIndex",
        components: {
            'memory-summary' : MemorySummary,
            'no-records' : NoDataRegister
        },
        data: {
            acAppData: window.obj_ac_app,
            items: [],            
        },
        mounted: function(){
            this.getData();
        },
        methods: {
            getData: function(){
                getAllMemories().then(result => {
                    const response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }                         
                    this.items = response.data.map(e => {
                        e.media = [];
                        return formatter89(e,this.acAppData.storage_url);
                    });
                }).catch(ex => {
                    const target_process = "Recuperar elementos"; 
                    StatusHandler.Exception(target_process,ex);
                })
            },
            onReadMemory: function(id){
                window.location.replace(this.acAppData.base_url + "/site/biografias/"+id);
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
            }
        }
    });
}

