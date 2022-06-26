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
                        
                        <DatePicker v-model="itemData.memory.birth_dateparse" 
                            type="date" 
                            confirm  
                            format="DD/MM/YYYY" 
                            :clearable="false"
                            :editable="false">
                        </DatePicker>
                                                         
                    </div>                                           
                    <h5 v-if="itemData.memory.type == 'memory'">Fecha de fallecimiento </h5>                    
                    <div v-if="itemData.memory.type == 'memory'" class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <DatePicker
                            v-model="itemData.memory.death_date"
                            format="DD-MM-YYYY"
                            type="date"
                            placeholder="Ejem. 01/11/2000">
                        </DatePicker>                                           
                    </div>                        
                </div>
                <div class="col-12 col-md-5">
                    <div class="wrpp-img-presentation" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]" >
                        
                    </div>
                    <button  @click.self.prevent="$refs.filePrincipalPic.click()" class="btn btn-block btn-secondary btn-flat">Subir</button>
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
                :item-data="{media: itemData.media}"
                @drop-ids="setMediaDrops">
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
        components: { VueEditor,MediaComponent,DatePicker},
        props: {
            pdata: {type: Object, required: true}
        },
        data(){
            return{
                itemData: JSON.parse(JSON.stringify(this.pdata)),
                update_mode: this.pdata.memory.id != 0 ? true: false,
                acAppData: {},
                flags: {
                    F1: false
                },

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
        created: function(){
            this.loadLocalValues();
        },
        mounted: function(){
            this.acAppData = window.obj_ac_app;
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
            loadLocalValues: function(){
                //Los parseos de fechas van aca, debido a que el parse en el formateo se convierne nuevamente 
                //a tipo string en el parseo local de prop a valor local 
                this.itemData.memory.birth_dateparse = 
                    this.update_mode ? new Date(this.itemData.memory.birth_date) : new Date("2000-01-02");

                this.itemData.memory.death_dateparse = 
                    this.update_mode && this.itemData.memory.death_date ? (new Date(this.itemData.memory.death_date)) : new Date();
            },
            setMediaDrops: function(arr){
                this.itemData.mediadrop_ids = JSON.parse(JSON.stringify(arr));
            },
            setPresentationImg: function(base64_img){
                let index = -1;
                //Si ya cargo una pero vuelve a cargar otra se hace replace 
                for(let e in this.itemData.media){
                    if(this.itemData.media[e].type_file == "image" && this.itemData.media[e].presentation == true && this.itemData.media[e].id == 0){
                        index = e
                        console.log("La imagen ya existe, se va a cargar en " + index);
                        break;
                    }
                }

                //Aqui me quede, se esta pasando la misma imagen
                //Objeto con datos de imagenes 
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
                    this.itemData.media[index] = img_add;
                }                
            },
            onCancel: function(){

            },
            onSave: function(){
                //Realizar validaciones 
                if(!this.$refs.acFrmMemoryItem.checkValidity()){
                    this.$refs.acFrmMemoryItem.classList.add('was-validated');
                    StatusHandler.ValidationMsg("Ingrese todos los campos requeridos");
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
            setPrincipalPic: function(event){
                this.$emit("trim-principal-img",event.target.files[0]);
            }
        } 
    }
</script>
