<template>
    <div class="card card-widget" style="width: 100%;max-width: 600px;margin: auto;">
        <div class="card-header p-2">
            <div class="user-block">
                <img class="img-circle" :src="model.post.img_owner" alt="User Image">
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
        <div class="card-body p-2">
            <div v-if="has_cap('aprobar-publicaciones')" class="form-group  m-0">
                <div  class="custom-control custom-switch custom-switch-on-success">
                    <input type="checkbox"  class="custom-control-input" id="switchApprovedPost" :checked="validate_approved(model.post.status)" @change="switchStatePost"/>
                    <label v-if="model.post.status == 'review' " class="custom-control-label" for="switchApprovedPost">Aprueba este elemento para que sea visible
                        para todos</label>
                    <label v-if="model.post.status == 'approved' " class="custom-control-label" for="switchApprovedPost">El elemento ha sido aprobado</label>                        
                </div>
            </div>            

            <div v-if="has_cap('destacar-publicaciones')" class="form-group">
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
                        <span class="description-text">Gratis</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-warning h4"><i class="fas fa-calendar-alt"></i></span>
                        <h5 class="description-header">Fecha a realizarse</h5>
                        <span class="description-text">26/05/2024</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-success h4"><i class="fas fa-redo-alt"></i></span>
                        <h5 class="description-header">Se repite</h5>
                        <span class="description-text">Cada año</span>
                    </div>
                    <!-- /.description-block -->
                </div>
            </div>
            <p>{{model.post.description}}</p>
            <!--COMPONENTE GALLERY COMPONENTE (PREVIEW MEDIA)-->
            <preview-media @source-files="onSourceFiles" :media="media_visuals"></preview-media>
            <!--END COMPONENTE GALLERY COMPONENTE (PREVIEW MEDIA)-->
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
                post_approved: this.model.post.status == "review"?false:true,
                post_delete: false,
                media_visuals: [], //para imagenes y videos 
                media_docs: [], //van aparte 
                id_img_selected: 0
            }
        },
        mounted: function(){
            this.filterMedia(this.model.media);
        },
        watch: {
            model: function(e){
                this.filterMedia(e.media);
            }
        },
        methods: {
            onSourceFiles: function(e){
                this.$emit("source-files",e);
            },
            filterMedia: function(media){
                if(media === undefined){return;} 

                this.media_visuals = [];
                this.media_docs = [];
                for(let gm of media){
                    if(gm.type_file === "image" || gm.type_file === "video"){
                        this.media_visuals.push(gm);
                        continue;
                    }
                    if(gm.type_file === "docfile"){
                        this.media_docs.push(k);
                    }                    
                }

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
                    new_state = this.model.post.status === 'approved' ? 'review':'approved';            
                }
                
                let valid_values = ['review','approved'];
                if(valid_values.indexOf(new_state) === -1){
                    StatusHandler.ValidationMsg("Inconsistencia en los valores, recargue el sitio");
                    return;
                };
                
                this.model.post.status = new_state;

                let data = {
                    id: this.model.post.id,
                    new_state: new_state
                };

                //Necesario estar logeado, y tener los permisos
                axios.post(`/api/post/setState`,data).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        this.model.post.status = last_state;//rollback state                
                        return;
                    }                    
                }).catch(ex=>{
                    let target_process = "Establecer estado del elemento"; 
                    StatusHandler.Exception(target_process,ex);
                    this.model.post.status = last_state;  //rollback state
                });

            },
            has_cap(e){
                return window.has_cap == undefined ? false : window.has_cap(e);
            }            
        }
    }
</script>
