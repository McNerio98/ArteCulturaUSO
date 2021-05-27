<template>
    <div class="card card-widget">
        <div class="card-header">
            <div class="user-block">
                <img class="img-circle" :src="paths.media_profiles + model.post.img_owner" alt="User Image">
                <span class="username"><a href="#">{{model.post.artistic_name == null?model.post.name:model.post.artistic_name}}</a></span>
                <span class="description">{{model.post.title}}</span>
            </div>
            <!-- /.user-block -->
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-toggle="tooltip" data-placement="right"
                    title="Editar elemento">
                    <i class="fas fa-pen"></i> Editar
                </button>
                <button type="button" class="btn btn-tool" data-toggle="tooltip" data-placement="right"
                    title="Eliminar elemento">
                    <i class="fas fa-trash-alt"></i> Remover
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="form-group  m-0">
                <div class="custom-control custom-switch custom-switch-on-success">
                    <input type="checkbox"  class="custom-control-input" id="switchApprovedPost" :checked="validate_approved(model.post.status)" @change="switchStatePost"/>
                    <label v-if="model.post.status == 'review' " class="custom-control-label" for="switchApprovedPost">Aprueba este elemento para que sea visible
                        para todos</label>
                    <label v-if="model.post.status == 'approved' " class="custom-control-label" for="switchApprovedPost">El elemento ha sido aprobado</label>                        
                </div>
            </div>            
            <div class="form-group">
                <div class="custom-control custom-switch custom-switch-on-success">
                    <input type="checkbox" v-model="model.post.is_popular" class="custom-control-input" id="switchPopularPost" @change="setPostPopular" />
                    <label v-if="! model.post.is_popular" class="custom-control-label" for="switchPopularPost">Marcar elemento como destacado </label>
                    <label v-else class="custom-control-label" for="switchPopularPost">Marcado como elemento destacado</label>
                </div>
            </div>
            <blockquote v-if="model.post.status == 'review' " class="quote-secondary">
                <p>El elemento actual se encuentra en <b>revisión.</b> Deberá ser aprobado por los administradores para ser
                    visible para todos los usuarios.</p>
                <small>Informe de estado del elemento</small>
            </blockquote>
            <div class="row" v-if="model.post.type == 'event' ">
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-success h4"><i class="fas fa-dollar-sign"></i></span>
                        <h5 class="description-header">Costo de Entrada</h5>
                        <span class="description-text">Costo de Entrada</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-warning h4"><i class="fas fa-calendar-alt"></i></span>
                        <h5 class="description-header">Fecha a realizarse</h5>
                        <span class="description-text">Fecha a realizarse</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-success h4"><i class="fas fa-redo-alt"></i></span>
                        <h5 class="description-header">Se repite</h5>
                        <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                </div>
            </div>
            <p>{{model.post.description}}</p>
            <!--Aqui van las imagenes-->
            <div>
                <div class="text-center">
                    <img style="width: 100%" :src="path_img_selected" alt="">
                </div>
                <div class="container-img-hscroll">
                    <div v-for="e of media_img" 
                    :key="e.id" 
                    class="circular-acimg"
                    :class="{selected: id_img_selected == e.id}" 
                    @click="selectImage('/'+e.path_file + e.name)"
                    :style="{ backgroundImage: 'url(' + '/'+e.path_file + e.name + ')' }">
                    </div>
                </div>    
            </div>        
        </div>
    </div>
</template>

<script>
    

    export default {
        props: {
            model: {
                type: Object,
                default: function(){
                    return {
                        post: {
                            id: 0,
                            title: "",
                            description: "",
                            type: "post",
                            creator_id: 0,
                            is_popular: 0,
                            status: "review",
                            created_at: "",
                            name: "",
                            artistic_name: "",
                            img_owner: ""                    
                        },
                        media: [],
                        meta: []
                    }
                }
            }
        },      
        data: function(){
            return {
                paths: {
                    media_profiles: "/files/profiles/",
                    files_docs: "/files/pdfs/",
                    files_images: "/files/images/",                    
                },                
                post_approved: this.model.post.status == "review"?false:true,
                post_delete: false,
                media_img: [],
                media_doc: [],
                media_video: [],
                id_img_selected: 0,
                path_img_selected: "/images/no_image_found.png"
            }
        },
        created: function(){
            console.log("Elemento PostGeneral creado");
        },
        mounted: function(){
            console.log("Elemento PostGeneral montado");
            console.log(this.model.media);
            this.filterMedia(this.model.media);
        },
        watch: {
            model: function(e){
                this.filterMedia(e.media);
            }
        },
        methods: {
            filterMedia: function(media){
                if(media === undefined){return;} 
                //filtrando
                this.media_img = [];
                this.media_doc = [];
                this.media_video = [];

                for(let k of media){
                    if(k.type_file == "image"){
                        this.media_img.push(k);
                        continue;
                    }
                    if(k.type_file == "video"){
                        this.media_video.push(k);
                        continue;  
                    }
                    
                    if(k.type_file == "docfile"){
                        this.media_doc.push(k);
                    }                    
                }

                if(this.media_img.length > 0){ //selecionar la primer imagen 
                    this.id_img_selected = this.media_img[0].id;
                    this.path_img_selected = "/"+this.media_img[0].path_file+this.media_img[0].name;
                }
            },
            selectImage: function(path){
                this.path_img_selected = path;
            },
            setPostPopular: function(){
                let current_sate = this.model.post.is_popular;
                let last_state = current_sate == true?false:true;
                if(this.model.post.status === 'review' && current_sate == true){
                    //rollback state 
                    this.model.post.is_popular = last_state;
                    StatusHandler.ValidationMsg("El elemento debe ser aprobado primero");
                    return;
                }
                let data = {
                    id: this.model.post.id,
                    new_state: current_sate
                };
                axios.post(`/api/post/setPopular`,data).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        //rollback state 
                        this.model.post.is_popular = last_state;                        
                        return;
                    }
                    let status = {id: this.model.post.id,is_popular:current_sate};
                    this.$emit('change-popular',status);
                }).catch((ex)=>{
                    let target_process = "Establecer Elemento como destacado"; 
                    let msg = "El proceso ("+target_process+")no se ha podido completar, póngase con soporte técnico."
                    StatusHandler.ShowStatus(msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    console.error(ex.response);
                    //rollback state 
                    this.model.post.is_popular = last_state;
                });
            },
            validate_approved: function(state){
                let bool = false;
                if(state === 'approved'){
                    bool = true;
                }
                return bool;
            },
            switchStatePost: function(event, state = null){
                let last_state = this.model.post.status; 
                let new_state = "";
                if(state === null){    
                    new_state = this.model.post.status === 'approved' ? 'review':'approved'; //sincronizando con el view                     
                }else if(state === 'delete'){
                    new_state = 'delete';
                }
                
                let valid_values = ['review','approved','delete'];
                if(valid_values.indexOf(new_state) === -1){
                    StatusHandler.ValidationMsg("Inconsistencia en los valores, recargue el sitio");
                    return;
                };
                
                this.model.post.status = new_state;
                
                let data = {
                    id: this.model.post.id,
                    new_state: new_state
                };

                axios.post(`/api/post/setState`,data).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        //rollback state
                        this.model.post.status = last_state;                         
                        return;
                    }                    
                }).catch((ex)=>{
                    let target_process = "Establecer estado del elemento"; 
                    let msg = "El proceso ("+target_process+")no se ha podido completar, póngase con soporte técnico."
                    StatusHandler.ShowStatus(msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    console.error(ex.response);
                    //rollback state
                    this.model.post.status = last_state; 
                });
            }
        }
    }
</script>
