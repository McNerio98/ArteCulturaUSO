import ResourceSummary from '../../components/recursos/ResourceShowCardComponent.vue';
import ResourceShow from '../../components/recursos/ResourceShowComponent.vue';
import {formatter91} from '../../formatters';
import { getAllResources,getResource } from '../../service';
import NoDataRegister from '../../components/NoDataRegister.vue';

//Show
if(document.getElementById("appResourcesShow") != undefined){
    const appResourcesShow = new Vue({
        el: "#appResourcesShow",
        components: {
            'resource' : ResourceShow
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
                    window.location.replace(this.acAppData.base_url  + "/site/recursos");
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
            onEditResource: function(){

            }
        }        
    });
}

//Index
if(document.getElementById("appResourcesIndex") != undefined){
    const appResourcesIndex = new Vue({
        el: "#appResourcesIndex",
        components: {
            'resource-summary' : ResourceSummary,
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
                window.location.replace(this.acAppData.base_url + '/site/recursos/' + id)
            }
        }
    });
}


