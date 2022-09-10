<template>
    <div class="pnl-memory card">
            <div class="card-header">
                <div class="card-tools p-2">
                    <button @click="onClickEdit" type="button" class="btn btn-tool" data-toggle="tooltip" 
                        v-if="has_cap('editar-reseñas') || itemData.memory.creator_id === acAppData.current_user.id"
                        data-placement="right"
                        title="Editar elemento">
                        <i class="fas fa-pen"></i> Editar
                    </button>
                    
                    <button @click="onClickDelete" type="button" class="btn btn-tool" data-toggle="tooltip" 
                        v-if="has_cap('eliminar-reseñas') || itemData.memory.creator_id === acAppData.current_user.id"
                        data-placement="right"
                        title="Eliminar elemento">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </div>
            </div>
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
                        <div class="wrpp-img-presentation" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]" >
                        </div>
                    </div>                          
              </div>

            <div class="row mb-1 mt-1">
                <div class="col-12">
                    <div v-html="itemData.memory.content"></div>                    
                </div>
            </div>

            <!--MOSTRAR FOTOS Y VIDEOS-->
            <h5>Archivos multimedia adjuntos</h5>
            <div class="row">
                <div v-for="(m,index) of ListImagesOrVideos" 
                    :key="index"
                    class="col-6 col-md-4">

                    <div v-if="m.type_file === 'image' && !m.presentation">
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

                    <div v-if="m.type_file === 'video' && !m.presentation">
                        <div class="image-area"
                            data-toggle="tooltip"
                            data-placement="top"
                            :title="m.name">
                            <a :href="'https://youtu.be/'+m.name" target="_blank">
                            <img :ref="'image' + index"
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
            <h5 class="mt-2">Documentos adjuntos</h5>
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

    import {deleteMemory} from '../../service';
    export default {
        props: {
            pdata: {type: Object,required:true}
        },
        data: function(){
            return {
                isDeleting: false,
                itemData: JSON.parse(JSON.stringify(this.pdata)),
                acAppData: {}
            }
        },
        computed: {
            srcPresentationImg: function(){
                if(this.itemData.presentation_model == null){
                    return this.acAppData.base_url + "/images/no_image_found.png";
                }else{
                    return this.itemData.presentation_model.url;
                }
            },            
            ListImagesOrVideos: function(){
                return this.itemData.media.filter((e,index) => {
                    if((e.type_file == "image" || e.type_file == "video") && !e.presentation){
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
        },
        methods: {
            onClickEdit: function(){
                this.$emit('edit',this.itemData.memory.id);
            },
            onClickDelete: function(){
                var vm = this;
                Swal.fire({
                    title: '¿Está seguro de eliminar?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar ',
                    denyButtonText: `Cancelar`,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                            vm.isDeleting = true;
                            deleteMemory(vm.itemData.memory.id).then(result => {
                                const response = result.data;
                                if(response.code == 0){
                                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                                    return;
                                }                
                                vm.isDeleting = false;
                                vm.$emit('deleted',vm.itemData.memory.id);
                            }).catch(ex => {
                                StatusHandler.Exception("Eliminar elemento",ex);
                                vm.isDeleting = false;
                            });
                        }else{
                            vm.isDeleting = false;
                        }
                });   
            },
            has_cap(e){
                return window.has_cap == undefined ? false : window.has_cap(e);
            }               
        }
    }
</script>
