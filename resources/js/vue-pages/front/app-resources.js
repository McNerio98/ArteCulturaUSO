import ResourceSummary from '@/components/recursos/ResourceShowCardComponent.vue';
import ResourceShow from '@/components/recursos/ResourceShowComponent.vue';
import {formatter91,formatter87} from '@/formatters';
import { getAllResources,getResource,getTiposRecursos } from '@/service';
import NoDataFound from '../../components/NoDataFound.vue';
import PaginationComponent from '@/components/pagination/PaginationComponent.vue';
Vue.component('media-viewer', require('@/components/media/ViewMediaComponent.vue').default);
Vue.component('spinner1',require('@/components/spinners/Spinner1Component.vue').default);

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
                window.location.href = this.acAppData.base_url;
            },
            onPromo: function(id){
                window.location.href = this.acAppData.base_url + `/admin/promociones/create?tarid=${id}&tartype=resource`;
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
            'no-records-found' : NoDataFound,
            'pagination' : PaginationComponent
        },
        data: {
            acAppData: window.obj_ac_app,
            recursoTypes: [],
            filterSelected: null,
            routeDynamic: null,
            componentPagKey: 100,
            showPagination: true,            
            items: [],
            isGettingResources: false
        },
        created: async function(){
            //this.getDataResources();
            this.getTiposRecursosData();
        },        
        methods: {
            getTiposRecursosData: function(){
                getTiposRecursos().then(result => {
                    //const response = result.data;
                    const response = result;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }     
                    this.recursoTypes = response.data;
                    this.onSelectFilter("ALL");
                }).catch(ex => {
                    const target_process = "Recuperar tipos de recursos"; 
                    StatusHandler.Exception(target_process,ex);
                })
            },
            onSelectFilter: function(selected){
                if(this.isGettingResources){return;} //Si esta obteniendo esperar 
                this.filterSelected = selected;
                this.getData();
            },  
            getData: function(){
                if(this.filterSelected == null){return;}
                this.isGettingResources = true;
                this.showPagination = true;
                this.routeDynamic = getAllResources(this.filterSelected);
                this.componentPagKey += 1;
            },       
            onLoadData: function(dataPag){
                this.showPagination = (dataPag.length > 0); 
                this.items = dataPag.map(e => {
                    e.media = []; //no lo trae por temas de carga y que no lo necesita en apartado de mostrar todos
                    return formatter91(e,this.acAppData.storage_url)
                });   
                //Despues de recuperar, remover el loading
                this.isGettingResources = false;               
            },
            onReadResource: function(id){
                window.location.replace(this.acAppData.base_url + '/site/recursos/' + id)
            }
        }
    });
}


