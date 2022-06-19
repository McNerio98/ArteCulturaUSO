<template>
    <div class="pnl-memory card">
        <div class="card-body">
            <div class="row">
                    <div class="col-12 col-md-7">
                        <h5>Talento/rubro</h5>                    
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                            </div>
                            <input disabled="true" 
                                :value="itemData.memory.area" 
                                type="text" 
                            class="form-control">
                        </div>

                        <h5>Nombre completo</h5>                    
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                            </div>
                            <input disabled="true" :value="itemData.memory.name" type="text" class="form-control" >         
                        </div>   

                        <h5>Otros nombres</h5>                    
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                            </div>
                            <input disabled="true" :value="itemData.memory.other_name" type="text" class="form-control">
                        </div>   

                        <h5>Fecha nacimiento</h5>                    
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                             <input disabled="true" :value="itemData.memory.birth_date | DateFormatES3" type="text" class="form-control" >                      
                        </div>    

                        <h5 v-if="itemData.memory.type == 'memory'">Fecha de fallecimiento </h5>                    
                        <div v-if="itemData.memory.type == 'memory'" class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input disabled="true" :value="itemData.memory.death_date | DateFormatES3" type="text" class="form-control">
                        </div>   
                    </div>

                    <div class="col-12 col-md-5">
                        <div class="wrpp-img-presentation" :style="[itemData.memory.img_presentation != null ? { backgroundImage: 'url(' +  itemData.memory.img_presentation.name + ')' }:'']" >
                        </div>
                    </div>                          
              </div>

            <div class="row mb-1 mt-1">
                <div class="col-12">
                    <div v-html="itemData.memory.content"></div>                    
                </div>
            </div>

            <!--MOSTRAR FOTOS Y VIDEOS-->
            <h4>Archivos multimedia adjuntos</h4>
            <div class="row">
                <div v-for="(m,index) of ListImagesOrVideos" 
                    :key="index"
                    class="col-6 col-md-4">

                    <div v-if="m.type_file === 'image'">
                        <div class="image-area"
                            data-toggle="tooltip"
                            data-placement="top"
                            :title="m.name">
                            <img style="object-fit: contain; padding-top: 3px"
                                width="100%"
                                height="100px"
                                :src="m.url"
                            alt="Preview"/>
                        </div>
                    </div>

                    <div v-if="m.type_file === 'video'">
                        <div class="image-area"
                            data-toggle="tooltip"
                            data-placement="top"
                            :title="m.name">
                            <a :href="'https://youtu.be/'+m.name" target="_blank">
                            <img :ref="'image' + key"
                                style="object-fit: contain; padding-top: 3px"
                                width="100%"
                                height="100px"
                                :src="acAppData.base_url + '/images/youtube_media_cmp.jpg'"
                                alt="Preview"/>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!--SECCION PARA MOSTRAR SOLO LOS DOCUMENTOS-->
            <h4>Documentos adjuntos</h4>
            <ul class="list-unstyled">
                <li v-for="(m, index) of ListDocs" v-bind:key="index" class="docfile mb-2" :title="m.name">
                    <a target="_blank" :href="m.url" class="btn-link text-secondary"><i class="far fa-file-pdf"></i> {{m.name}}</a>
                </li>
            </ul>                

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
    export default {
        props: {
            itemData: {type: Object,required:true}
        },
        data: function(){
            return {
                acAppData: {}
            }
        },
        computed: {
            ListImagesOrVideos: function(){
                return this.itemData.media.filter((e,index) => {
                    if(e.type_file == "image" || e.type_file == "video"){
                        e.index_parent = index;
                        return e;
                    }
                });;
            },

            ListDocs: function(){
                return this.itemData.media.filter((e,index) => {
                    if(e.type_file == "docfile"){
                        e.index_parent = index;
                        return e;
                    }
                });;
            }
        },     
        mounted() {
            this.acAppData = window.obj_ac_app;
        }
    }
</script>
