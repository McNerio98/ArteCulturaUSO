<template>
    <div>
        <div class="bg1rc p-0 p-md-3">
            <div class="row ">
                <div class="col">
                    <div class="acm-img border1rc" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]">
                    </div>
                </div>
                <div class="col-8">
                    <div>
                            <p>Tipo de recurso: {{getTipo}}</p>
                            <h3>{{itemData.resource.name}}</h3>
                            <p>Contiene: {{itemData.media.length}} archivos adjuntos</p>
                    </div>
                    <div>
                        <button href="#" class="btn btn-default btn-sm float-right mr-2" 
                            v-if="has_cap('crear-promociones')"
                            @click="onPromo">
                            <i class="fas fa-pen"></i> 
                            Promocionar
                        </button>                        
                        <button href="#" class="btn btn-default btn-sm float-right mr-2" 
                            v-if="has_cap('editar-recursos') || itemData.resource.creator_id === acAppData.current_user.id"
                            @click="onEdit">
                            <i class="fas fa-pen"></i> 
                            Editar
                        </button>
                        <button href="#" class="btn btn-default btn-sm float-right mr-2" 
                            v-if="has_cap('eliminar-recursos') || itemData.resource.creator_id === acAppData.current_user.id"
                            @click="onDelete">
                            <i class="fas fa-trash-alt"></i>
                            Eliminar
                        </button>                        
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <p v-html="itemData.resource.description"></p>
            </div>
        </div>
        <div>

        <div class="card-footer bg-white">

            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                <li v-for="(e) in ListDocs" :key="e.id">
                    <span class="mailbox-attachment-icon">
                        <i v-if="isPDF(e.name)" class="far fa-file-pdf"></i>
                        <i v-else class="far fa-file-word"></i>
                    </span>
                    <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> {{getShortName(e.name)}}</a>
                        <span class="mailbox-attachment-size clearfix mt-1">                         
                            <a :href="e.url" class="btn btn-default btn-sm float-right mr-2" download>
                                <i class="fas fa-cloud-download-alt"></i>
                                Descargar
                            </a>
                        </span>
                    </div>
                </li>
            </ul>

        </div>
        </div>
    </div>
</template>

<script>
import {getTiposRecursos,deleteResource} from '../../service';
export default {
    props: {
        pdata: {type: Object,required:true}
    },  
    data: function(){
        return {
            isDeleting: false,
            tiposRecursos: [],
            acAppData: window.obj_ac_app,
            itemData: JSON.parse(JSON.stringify(this.pdata))            
        }
    },
    created: function(){
        getTiposRecursos().then(result =>{
            this.tiposRecursos = result.data;
        });        
    },
    computed: {
        srcPresentationImg: function(){
            if(this.itemData.presentation_model == null){
                return this.acAppData.base_url + "/images/default_book.jpg";
            }else{
                return this.itemData.presentation_model.url;
            }
        },
        getTipo: function(){
            if(this.tiposRecursos.length > 0){
                return this.tiposRecursos[this.itemData.resource.tipo_id - 1].option;
            }else{
                return "No Especificado";
            }
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
    methods: {
        isPDF: function(namefile){
             const extension = namefile.substring(namefile.lastIndexOf('.')+1, namefile.length);
             return  extension.toLowerCase().trim() == "pdf" ? true : false;
        },
        getShortName: function(namefile){
            const maxlength = 25;
            const extension = namefile.substring(namefile.lastIndexOf('.')+1, namefile.length);
            if(namefile.length > maxlength){
                return namefile.substring(0,maxlength) + "."+extension
            }
            return namefile;
        },
        onEdit: function(){
            this.$emit('edit',this.itemData.resource.id);
        },
        onDelete: function(){
            var vm = this;
            Swal.fire({
                title: '¿Está seguro de eliminar?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Eliminar ',
                denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                        vm.isDeleting = true;
                        deleteResource(vm.itemData.resource.id).then(result => {
                                const response = result.data;
                                if(response.code == 0){
                                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                                    return;
                                }                
                                vm.isDeleting = false;
                                vm.$emit('deleted',vm.itemData.resource.id);
                        }).catch(ex => {
                            StatusHandler.Exception("Eliminar elemento",ex);
                            vm.isDeleting = false;
                        });
                    }else{
                        vm.isDeleting = false;
                    }
                });            
        },
        onPromo: function(){
            this.$emit('on-promo',this.itemData.resource.id);
        },
        has_cap(e){
            return window.has_cap == undefined ? false : window.has_cap(e);
        }             
    }
}
</script>


<style scoped>
    .acm-img{
        background-color: gray;
        width: 100%;
        margin: auto;
        max-width: 100px;
        aspect-ratio: 2/3;
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Do not repeat the image */
        background-size: cover; /* Resize the background image to cover the entire container */        
    }

    .bg1rc{
        background-color: #d2d6de;
    }

    .border1rc{
        border: 4px solid #363333
    }    
</style>