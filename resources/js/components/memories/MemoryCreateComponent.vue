<template>
    <div class="pnl-memory card">
        <form class="card-body" ref="acFrmMemoryItem">
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input"  v-model="type" value="biography" type="radio" id="customRadio1" name="typeMemories">
                        <label for="customRadio1" class="custom-control-label">Crear biografía</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input"  v-model="type" value="memory" type="radio" id="customRadio2" name="typeMemories">
                        <label for="customRadio2" class="custom-control-label">Crear homenaje</label>
                    </div>                    
                    <h5>Talento/rubro</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input v-model="area" type="text" class="form-control" placeholder="Músico, escritor, pintor …" minlength="2" maxlength="150" required>
                        <div class="invalid-feedback">
                            Ingrese un valor valido
                        </div>                          
                    </div>                    
                      
                    <h5>Nombre completo</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input v-model="name" type="text" class="form-control" placeholder="Nombre completo" minlength="2" maxlength="100" required>
                        <div class="invalid-feedback">
                            Ingrese un valor valido
                        </div>                        
                    </div>   
                    <h5>Otros nombres</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input v-model="other_name" type="text" class="form-control" minlength="2" maxlength="150" placeholder="Otros nombres">
                    </div>                       
                    <h5>Fecha nacimiento</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <date-picker
                            v-model="birth_date"
                            format="DD-MM-YYYY"
                            type="date"
                            placeholder="Ejem. 01/07/1990"
                        ></date-picker>                        
                    </div>                                           
                    <h5 v-if="type == 'memory'">Fecha de fallecimiento </h5>                    
                    <div v-if="type == 'memory'" class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <date-picker
                            v-model="death_date"
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
                    <textarea v-model="content" class="form-control" name="" id="" rows="10" required></textarea>
                    <div class="invalid-feedback">
                        Ingrese un valor valido
                    </div>                     
                </div>
            </div>
            <div>
                <a class="btn btn-app">
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
    export default {
        props: {
            editMode: {type: Boolean, default: false},
        },
        data(){
            return{
                area: "",
                name: "",
                other_name: "",
                content: "",
                birth_date: new Date("2000-01-02"),
                death_date: new Date(),
                type: "biography",
                multimedia: [],
                spinners: {S1: false},
                presentation_img_preview: null
            }
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

                if(this.area.length < 2 || this.area.length > 150){
                    StatusHandler.ValidationMsg("Rubro,Talento / Longitud no valida (mayor a 1 y menor a 150)");
                    return;
                }

                if(this.area.name < 2 || this.area.name > 100){
                    StatusHandler.ValidationMsg("Nombre / Longitud no valida (mayor a 1 y menor a 100)");
                    return;
                }

                if(this.editMode){
                    this.updateMemory();
                }else{
                    this.createMemory();
                }                 
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
