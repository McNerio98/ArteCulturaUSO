<template>
    <div class="card card-widget mb-1 mb-md-3" style="width: 100%;max-width: 600px;margin: auto;">
        <div class="card-header p-2">
            <div class="user-block">
                <img class="img-circle" :src="itemData.creator.profile_img" alt="User Image">
                <span class="username"><a href="#">{{itemData.creator.nickname == null?itemData.creator.name:itemData.creator.nickname}}</a></span>
                <span class="description">{{itemData.post.title}}</span>
            </div>
            <!-- /.user-block -->
            <div class="card-tools">

                <button @click="onClickEdit" :disabled="disabled_controls" v-if="has_cap('editar-publicaciones') || itemData.creator.id === acAppData.current_user.id" type="button" class="btn btn-tool" data-toggle="tooltip" data-placement="right"
                    title="Editar elemento">
                    <i class="fas fa-pen"></i> Editar
                </button>
                <button @click="onClickDelete" :disabled="disabled_controls" v-if="has_cap('eliminar-publicaciones') || itemData.creator.id === acAppData.current_user.id" type="button" class="btn btn-tool" data-toggle="tooltip" data-placement="right"
                    title="Eliminar elemento">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>

            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body p-2">
            <!--
            <div v-if="has_cap('aprobar-publicaciones')" class="form-group  m-0">
                <div  class="custom-control custom-switch custom-switch-on-success">
                    <input type="checkbox"  class="custom-control-input" :id="'switchApprovedPost'+itemData.post.id" :checked="validate_approved(itemData.post.status)" @change="switchStatePost"/>
                    <label v-if="itemData.post.status == 'review' " class="custom-control-label" :for="'switchApprovedPost'+itemData.post.id">Aprueba este elemento para que sea visible
                        para todos</label>
                    <label v-if="itemData.post.status == 'approved' " class="custom-control-label" :for="'switchApprovedPost'+itemData.post.id">El elemento ha sido aprobado</label>                        
                </div>
            </div>            

            <div v-if="has_cap('destacar-publicaciones')" class="form-group">
                <div class="custom-control custom-switch custom-switch-on-success">
                    <input type="checkbox" v-itemData="itemData.post.is_popular" class="custom-control-input" :id="'switchPopularPost'+itemData.post.id" @change="setPostPopular" />
                    <label v-if="! itemData.post.is_popular" class="custom-control-label" :for="'switchPopularPost'+itemData.post.id">Marcar elemento como destacado </label>
                    <label v-else class="custom-control-label" :for="'switchPopularPost' + itemData.post.id">Marcado como elemento destacado</label>
                </div>
            </div>

            <blockquote v-if="itemData.post.status == 'review' " class="quote-secondary mt-0 ml-0 mr-0 mb-1 p-1" style="border-bottom: 1px solid #bfbcbc !important;">
                <small>El elemento actual se encuentra en <b>revisión.</b> Deberá ser aprobado por los administradores para ser
                    visible para todos los usuarios.</small>
            </blockquote>
             -->
            <div  class="row" v-if="itemData.post.type == 'event' ">
                <div class="col-12">
                    <div class="callout callout-info p-1 rounded-0 shadow-none">
                        <h5 class="p-0 m-0 text-success"><i class="fas fa-map-marker-alt"></i> Lugar</h5>
                         {{getDirection}}
                    </div>
                </div>
            </div>
            <div class="row" v-if="itemData.post.type == 'event' ">
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-success h4"><i class="fas fa-dollar-sign"></i></span>
                        <h5 class="description-header">Costo de Entrada</h5>
                        <span class="description-text">{{itemData.dtl_event.has_cost ? itemData.dtl_event.cost : "GRATIS"}}</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-warning h4"><i class="fas fa-calendar-alt"></i></span>
                        <h5 class="description-header">Fecha a realizarse</h5>
                        <span class="description-text">{{itemData.dtl_event.event_date | DateFormatES1}}</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-12">
                    <div class="description-block border-right">
                        <span class="text-success h4"><i class="fas fa-redo-alt"></i></span>
                        <h5 class="description-header">Se repite</h5>
                        <span class="description-text">{{itemData.dtl_event.frequency == "unique" ? 'FECHA ÚNICA ' : 'CADA AÑO'}}</span>
                    </div>
                    <!-- /.description-block -->
                </div>
            </div>
            <p><span v-html="getHTMLContent"></span></p>
            <!--COMPONENTE GALLERY COMPONENTE (PREVIEW MEDIA)-->
            <preview-media @source-files="onSourceFiles" :media="media_visuals"></preview-media>
            <!--END COMPONENTE GALLERY COMPONENTE (PREVIEW MEDIA)-->

            <!--DOCUMENTS-->
            <ul v-for="(m, key) in media_docs" v-bind:key="key" class="list-unstyled mb-2">
                <li class="docfile" :title="m.name">
                <a  target="_blank" :href="m.url" class="btn-link text-secondary"><i class="far fa-file-pdf"></i> {{m.name}}</a>                      
                </li>
            </ul>

        </div>
    </div>
</template>
<style scoped>
    li.docfile{
        background-color: #f0f0f0;
        padding: 5px 0;
        overflow: hidden;
        border-radius: 10px;
    }
</style>

<script>
    import {municipiosItems} from '../../utils';

    export default {
        props: {
            pdata: {type: Object,required:true},
            authId: {type: Number, default: 0}//Current user id 
        },      
        data: function(){
            return {       
                itemData: JSON.parse(JSON.stringify(this.pdata)),
                acAppData: window.obj_ac_app,
                post_approved: false,
                post_delete: false,
                media_visuals: [], //para imagenes y videos 
                media_docs: [], //van aparte 
                id_img_selected: 0,
                disabled_controls: false,
                municipios: []                
            }
        },
        created: function(){
            this.municipios = municipiosItems();
        },
        mounted: function(){
            this.filterMedia(this.itemData.media);
        },
        computed: {
            getHTMLContent: function(){
                if(this.itemData.post.description == undefined || this.itemData.post.description.length == 0){return ``;}
                //para enlaces
                var url_regex = /(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/g;
                var formatted = this.itemData.post.description.replace(url_regex,function(str, p1, offset, s){return `<a href="${str}" target="_blank">${str}</a>`;})
                //para espacios saltos de lineas
                formatted = formatted.replace(/\n/g, "<br>");
                return formatted;
            },
            getDirection: function(){
                if(this.municipios.length > 0){
                    let municipioName = this.municipios[this.itemData.dtl_event.address.municipio_id - 1].municipio;
                    return this.itemData.dtl_event.address.details + " " + municipioName + " Sonsonate";
                }else{
                    return "(No Especificado)";
                }
            }
        },
        methods: {
            regexLink: function(){

            },
            onClickEdit: function(){
                this.$emit('edit-item',this.itemData.post.id);
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
                        vm.disabled_controls = true;
                        axios.delete(`/postevent/${this.itemData.post.id}`).then(result=>{
                            let response = result.data;
                            if(response.code == 0){
                                StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                                return;
                            }  
                            vm.$emit('delete-item',this.itemData.post.id);
                             //Esto esta raro, porque guardaba el estado para el que quedaba en su posicion 
                             vm.disabled_controls = false;
                        }).catch(ex=>{
                            StatusHandler.Exception("Eliminar elemento",ex);
                            vm.disabled_controls = false;
                        });
                    }else{
                        vm.disabled_controls = false;
                    }
                });

            },
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
                        this.media_docs.push(gm);
                    }                    
                }

            },
            setPostPopular: function(){
                let current_sate = this.itemData.post.is_popular;
                let last_state = current_sate == true?false:true;
                if(this.itemData.post.status === 'review' && current_sate == true){
                    //rollback state 
                    this.itemData.post.is_popular = last_state;
                    StatusHandler.ValidationMsg("El elemento debe ser aprobado primero");
                    return;
                }
                let data = {
                    id: this.itemData.post.id,
                    new_state: current_sate
                };
                axios.post(`/post/setPopular`,data).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        //rollback state 
                        this.itemData.post.is_popular = last_state;                        
                        return;
                    }
                    let status = {id: this.itemData.post.id,is_popular:current_sate};
                    this.$emit('change-popular',status);
                }).catch((ex)=>{
                    let target_process = "Establecer Elemento como destacado"; 
                    StatusHandler.Exception(target_process,ex);
                    this.itemData.post.is_popular = last_state;
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
                let last_state = this.itemData.post.status; 
                let new_state = "";
                if(state === null){    
                    new_state = this.itemData.post.status === 'approved' ? 'review':'approved';            
                }
                
                let valid_values = ['review','approved'];
                if(valid_values.indexOf(new_state) === -1){
                    StatusHandler.ValidationMsg("Inconsistencia en los valores, recargue el sitio");
                    return;
                };
                
                this.itemData.post.status = new_state;

                let data = {
                    id: this.itemData.post.id,
                    new_state: new_state
                };

                //Necesario estar logeado, y tener los permisos
                axios.post(`/post/setState`,data).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        this.itemData.post.status = last_state;//rollback state                
                        return;
                    }                    
                }).catch(ex=>{
                    let target_process = "Establecer estado del elemento"; 
                    StatusHandler.Exception(target_process,ex);
                    this.itemData.post.status = last_state;  //rollback state
                });

            },
            has_cap(e){
                return window.has_cap == undefined ? false : window.has_cap(e);
            }            
        }
    }
</script>
