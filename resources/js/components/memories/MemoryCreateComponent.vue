<template>
    <div class="pnl-memory card">
        <form class="card-body" ref="acFrmMemoryItem">
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input"  v-model="itemData.memory.type" value="biography" type="radio" id="customRadio1" name="typeMemories">
                        <label for="customRadio1" class="custom-control-label">Crear biografía</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input"  v-model="itemData.memory.type" value="memory" type="radio" id="customRadio2" name="typeMemories">
                        <label for="customRadio2" class="custom-control-label">Crear homenaje</label>
                    </div>                    
                    <h5>Talento/rubro</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input v-model="itemData.memory.area" type="text" class="form-control" placeholder="Músico, escritor, pintor …" minlength="2" maxlength="150" required>
                        <div class="invalid-feedback">
                            Ingrese un valor valido
                        </div>                          
                    </div>                    
                      
                    <h5>Nombre completo</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input v-model="itemData.memory.name" type="text" class="form-control" placeholder="Nombre completo" minlength="2" maxlength="100" required>
                        <div class="invalid-feedback">
                            Ingrese un valor valido
                        </div>                        
                    </div>   
                    <h5>Otros nombres</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input v-model="itemData.memory.other_name" type="text" class="form-control" minlength="2" maxlength="150" placeholder="Otros nombres">
                    </div>                       
                    <h5>Fecha nacimiento</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <date-picker
                            v-model="itemData.memory.birth_date"
                            format="DD-MM-YYYY"
                            type="date"
                            placeholder="Ejem. 01/07/1990"
                        ></date-picker>                        
                    </div>                                           
                    <h5 v-if="itemData.memory.type == 'memory'">Fecha de fallecimiento </h5>                    
                    <div v-if="itemData.memory.type == 'memory'" class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <date-picker
                            v-model="itemData.memory.death_date"
                            format="DD-MM-YYYY"
                            type="date"
                            placeholder="Ejem. 01/11/2000"
                        ></date-picker>                                           
                    </div>                        
                </div>
                <div class="col-12 col-md-5">
                    <div class="wrpp-img-presentation" :style="[presentation_img_preview != null ? { backgroundImage: 'url(' +  presentation_img_preview + ')' }:'']" >
                        
                    </div>
                    <button  @click="$refs.filePrincipalPic.click()" class="btn btn-block btn-secondary btn-flat">Subir</button>
                    <input type="file" hidden="true" ref="filePrincipalPic" @change="this.setPrincipalPic"  id="inputFileImgPrincipalPic">
                </div>                
            </div>
            <div class="row mb-1 mt-1">
                <div class="col-12">
                    <VueEditor
                    v-model="itemData.memory.content"
                    :placeholder="editor_params.placeholder"
                    :editorToolbar="editor_params.customToolbar"
                    >
                    </VueEditor>
                    <div class="invalid-feedback">
                        Ingrese un valor valido
                    </div>                     
                </div>
            </div>
            
            <!--Se podria agregar otros nodos de ineteres-->
            <MediaComponent 
                :item-data="{files: itemData.files}">
            </MediaComponent>
            
            <div>
                <a class="btn btn-app" @click="onSave">
                    <i class="fas fa-save"></i> Guardar
                </a>                    
                <a class="btn btn-app" @click="onCancel">
                    <i class="fas fa-minus"></i> Cancelar
                </a>                 
            </div>
        </form>
    </div>
</template>
<style scoped>
    .pnl-memory{
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

    textarea{
        width: 100%;
    }

    .mx-datepicker {
        height: 100%;
        flex: 1 1 0%;
    }

.mx-input{
	height: 100% !important;
	font-size: 1rem !important;
}

</style>
<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/es';
    import { VueEditor } from "vue2-editor";
    import {upsertMemory} from '../../service';
    import MediaComponent from './MediaMemory.vue';
    

    export default {
        components: { VueEditor,MediaComponent},
        props: {
            itemData: {type: Object, required: true}
        },
        data(){
            return{
                acAppData: {},
                flags: {
                    F1: false
                },
                presentation_img_preview: null,

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
            this.acAppData = window.obj_ac_app;
        },

        methods: {
            setPresentationImg: function(base64_img){
                let index = -1;
                for(let e in this.media){
                    if(this.media[e].type == "image" && this.media[e].presentation == true){
                        index = e
                        break;
                    }
                }
                //Objeto con datos de imagenes 
                var img_add = {
                    id: null,
                    type: "image",
                    filename: "generated.jpg",
                    data: base64_img,
                    presentation: true
                }
                this.presentation_img_preview = img_add.data;
                if(index === -1){//Si no se encontro se agrega 
                    this.multimedia.push(img_add);
                }else{//Si se encontro se remplaza 
                    this.multimedia[index] = img_add;
                }                
            },
            onCancel: function(){

            },
            onSave: function(){
                //Realizar validaciones 
                if(!this.$refs.acFrmMemoryItem.checkValidity()){
                    this.$refs.acFrmMemoryItem.classList.add('was-validated');
                    return;
                }

                //mas validaciones aqui

                this.upsert()
            },
            upsert: function(){
                //Realizar cambios pertinentes 
                if(this.itemData.id == 0){

                }else{

                }

                upsertMemory(this.itemData).then(result =>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }

                    window.location.replace(this.acAppData.base_url + "/admin/memories/"+response.data.id);
                }).catch(ex=>{
                    let target_process = "Guarda informacion de elemento"; 
                    StatusHandler.Exception(target_process,ex);
                });

            },
            createMemory: function(){
                var data_send = {
                    area: this.area,
                    name: this.name,
                    other_name: this.other_name,
                    birth_date: this.birth_date,
                    content: this.content,
                    type: this.type,
                     media: [...this.multimedia] 
                }

                if(this.type == "memory"){
                    data_send.death_date = this.death_date;
                }

                axios.post("/memories",data_send).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }    
                    alert("SE guardo");
                }).catch(ex=>{
                    StatusHandler.Exception("Crear elemento",ex);
                }).finally(e=>{
                    this.spinners.S1 = false;
                });
            },
            updateMemory: function(){

            },
            addMedia: function(media){
                
            },
            setPrincipalPic: function(event){
                this.$emit("trim-principal-img",event.target.files[0]);
            }
        } 
    }
</script>
