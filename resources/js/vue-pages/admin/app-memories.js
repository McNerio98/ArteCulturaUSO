
/**
 * Dev: mario.nerio
 * Content file: list all, new, update memory item 
 */

Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

Vue.component('memory-create',require('../../components/memories/MemoryCreateComponent').default);
Vue.component('memory-summary',require('../../components/memories/MemoryMiniViewComponent').default);


Vue.component('control-trim', require('../../components/trim/TrimComponentv2.vue').default);
import {formatter89} from '../../formatters';
import {getMemory} from '../../service';
import Memory from '../../components/memories/MemoryShowComponent.vue';

//Show all items 
if(document.getElementById("appMemories") != undefined){
    const appMemories = new Vue({
        el: "#appMemories",
        data: {
    
        }
    });
}

//
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
                    window.location.replace(this.acAppData +"/" + "memories");
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
                    let target_process = "Establecer Elemento como destacado"; 
                    StatusHandler.Exception(target_process,ex);
                });            
            }
        }
    });
}

//New item or update  
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
                this.getMemory();
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
                    files: []
                },this.acAppData.storage_url));
            },
            getMemory: function(){

            },
            openTrimPrincipalPic: function(file){
                this.$refs.acVmCompCropper.openTrim(file);
                this.trim_buffer.target = "MAIN_IMG_MEMOY";
            },
            principalPicCropped: function(base64){
                switch(this.trim_buffer.target){
                    case "MAIN_IMG_MEMOY": {
                        //pass to component 
                        this.$refs.acVmCompMemory.setPresentationImg(base64);
                        break;
                    }
                }
                //pass chioldren component 
                this.trim_buffer.target = "";
            }
        }
    });
}
