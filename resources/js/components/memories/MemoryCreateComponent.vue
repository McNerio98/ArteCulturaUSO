<template>
    <div class="pnl-memory card">
        <div class="card-body">
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
                        <input type="email" class="form-control" placeholder="Email">
                    </div>                    
                    <h5>Nombre completo</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Nombre completo">
                    </div>   
                    <h5>Otros nombres</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Otros nombres">
                    </div>                       
                    <h5>Fecha nacimiento</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="dd/mm/yyyy">
                    </div>                                           
                    <h5 v-if="type == 'memory'">Fecha de fallecimiento </h5>                    
                    <div v-if="type == 'memory'" class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="dd/mm/yyyy">
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
                    <textarea name="" id="" rows="10"></textarea>
                </div>
            </div>
            <div>
                <a class="btn btn-app">
                    <i class="fas fa-save"></i> Guardar
                </a>                    
                <a class="btn btn-app">
                    <i class="fas fa-minus"></i> Cancelar
                </a>                 
            </div>
        </div>
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
</style>
<script>
    export default {
        props: {
            model: {type: Object, default: function(){
                return {
                    type: "biography"
                }
            }},
            mainImgChange: {require: false, default: false},
            mainImg: {require: false,default: null}
        },
        data(){
            return{
                 type: "biography",
                 media: [],
                 presentation_img_preview: null
            }
        },
        watch: {
            mainImgChange: function(newVal,oldVal){
                if(newVal === true){
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
                        filename: "",
                        data: this.mainImg,
                        presentation: true
                    }
                    this.presentation_img_preview = img_add.data;
                    //Si no se encontro se agrega 
                    if(index === -1){
                        this.media.push(img_add);
                    }else{
                    //Si se encontro se remplaza 
                        this.media[index] = img_add;
                    }
                }
            }
        },
        methods: {
            addMedia: function(media){
                
            },
            setPrincipalPic: function(event){
                this.$emit("trim-principal-img",event.target.files[0]);
            }
        } 
    }
</script>
