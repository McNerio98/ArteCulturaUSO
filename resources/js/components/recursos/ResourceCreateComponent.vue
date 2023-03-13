<template>
    <div class="pnl-resource card">
        <form class="card-body" ref="acFrmResourceItem">
             <div class="row">
                   <div class="col-12 col-md-7">
                        <div class="mb-3">
                            <label for="resourceType" class="form-label">Tipo de recurso</label>
                            <select v-model="itemData.resource.tipo_id" class="form-control" aria-label="Default select example" id="resourceType" required>
                                 <option value="0" selected disabled> - - SELECCCIONAR - - </option>
                                <option v-for="option in resourceTypes" :value="option.id" :key="option.id">{{option.name}}</option>
                            </select>                    
                        </div>    
                        <div class="mb-3">
                            <label for="resourceName" class="form-label">Nombre del recurso</label>
                            <input v-model="itemData.resource.name" class="form-control" type="text" placeholder="Nombre del recurso" aria-label="Nombre del recurso" id="resourceName">
                        </div>                
                   </div>    
                    <div class="col-12 col-md-5">
                        <div class="wrpp-img-presentation" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]" >
                            
                        </div>
                        <button  @click.self.prevent="$refs.filePrincipalPic.click()" class="btn btn-block btn-secondary btn-flat">Subir</button>
                        <input type="file" 
                            accept="image/png, image/jpg, image/jpeg"
                            hidden="true"
                            ref="filePrincipalPic" 
                            @change="this.setPrincipalPic"  
                            id="inputFileImgPrincipalPic"/>
                    </div>                               
             </div>
            <div class="row mb-1 mt-1">
                <div class="col-12">
                    <VueEditor
                    v-model="itemData.resource.description"
                    :placeholder="editor_params.placeholder"
                    :editorToolbar="editor_params.customToolbar"
                    />
                            
                    <div class="invalid-feedback">
                    Ingrese un valor valido
                    </div>                     
                </div>
            </div>             

            <!--Se podria agregar otros nodos de ineteres-->
            <MediaComponent 
                :item-data="{media: itemData.media}"
                @drop-ids="setMediaDrops">
            </MediaComponent>               
            <div>
                <button class="btn btn-app" :disabled="isSaving" @click="onSave">
                    <i class="fas fa-save"></i> Guardar
                </button>                    
                <button class="btn btn-app" :disabled="isSaving" @click="onCancel">
                    <i class="fas fa-minus"></i> Cancelar
                </button>                 
            </div>
        </form>
    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import MediaComponent from './MediaResource.vue';
import {upsertResource,getTiposRecursos} from '@/service';


export default {
    components: {
        VueEditor,
        MediaComponent
    },
    props: {
        pdata: {type: Object, required: true}
    },    
    data(){
        return {
            isSaving: false,
            acAppData: window.obj_ac_app,
            itemData: JSON.parse(JSON.stringify(this.pdata)),
            resourceTypes: [],
            editor_params: {
                placeholder: "Ingrese contenido ...",
                customToolbar: [
                    [{ header: [false, 1, 2, 3, 4, 5, 6] }],
                    ["bold", "italic", "underline"],
                    [{ list: "ordered" }, { list: "bullet" }],
                    [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                    ['link'],
                ]                    
            }            
        }
    },
    mounted: function(){
        this.getTiposRecursosData();
        
    },
    computed: {     
        srcPresentationImg: function(){
            //Buscar reciente 
            var img = this.itemData.media.filter(e => e.presentation == true && e.id == 0);

            if(img.length > 0){
                return img[0].data;
            }
            //Buscar en las precargadas para caso actualizacion 
            var img = this.itemData.media.filter(e => e.presentation == true);

            if(img.length  > 0){
                return img[0].url;
            }else{
                return this.acAppData.base_url + "/images/no_image_found.png";
            }
        }
    },
    methods: {
        getTiposRecursosData: function(){
            getTiposRecursos().then(result => {
                const response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }   

                this.resourceTypes = response.data;

            }).catch(ex => {
                const target_process = "Recuperar tipos de recursos"; 
                StatusHandler.Exception(target_process,ex);
            });
        },           
        setPrincipalPic: function(event){
            let file = null;

            if(event.target.files.length > 0){
                file = event.target.files[0];
                const validExten = ["jpeg","jpg","png"];
                let extenstion = file.name.substring(file.name.lastIndexOf('.')+1, file.name.length) || null;
                if(extenstion == null || !validExten.includes(extenstion.toLowerCase().trim())){
                         StatusHandler.ValidationMsg("Archivos no soportados");
                         return;
                }                
            }
            this.$emit("trim-principal-img",file);
        },
        setMediaDrops: function(){
            this.itemData.mediadrop_ids = JSON.parse(JSON.stringify(arr));
        },
        setPresentationImg: function(base64_img){
                let index = -1;
                //Si ya cargo una pero vuelve a cargar otra se hace replace 
                for(let e in this.itemData.media){
                    if(this.itemData.media[e].type_file == "image" && this.itemData.media[e].presentation == true){
                        index = e
                        break;
                    }
                }

                var img_add = {
                    id: 0,
                    type_file: "image",
                    name: "generated.jpg",
                    data: base64_img,
                    presentation: true
                }

                if(index === -1){//Si no se encontro se agrega 
                    this.itemData.media.push(img_add);
                }else{//Si se encontro se remplaza 
                    //Dejar de esta forma 
                    this.itemData.media[index].id = img_add.id;
                    this.itemData.media[index].type_file = img_add.type_file;
                    this.itemData.media[index].name = img_add.name;
                    this.itemData.media[index].data = img_add.data;
                    this.itemData.media[index].presentation = img_add.presentation;
                }                
        },        
        onCancel: function(){

        },
        onSave: function(){
            //Realizar proceso de validacion 
            this.upsert();
        },
        upsert: function(){
            this.isSaving = true;
            upsertResource(this.itemData).then(result =>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    this.isSaving = false;
                    return;
                }
                
                this.$emit('on-created',response.data.id);
            }).catch(ex => {
                this.isSaving = false;
                let target_process = "Guarda informacion de elemento"; 
                StatusHandler.Exception(target_process,ex);
            });
        }
    }
}
</script>

<style scoped>
    .pnl-resource{
        width: 100%;
        max-width: 800px;
        margin: auto;
    }

    .wrpp-img-presentation{
        background-color: #bfbfbf;
        position: relative;
        width: 100%;
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Do not repeat the image */
        background-size: cover; /* Resize the background image to cover the entire container */        
        padding-top: 130%; /* 1:1 Aspect Ratio */
    }        
</style>