<template>
    <div>
        <div class="bg1rc p-0 p-md-3">
            <div class="row ">
                <div class="col">
                    <div @click="onPresentationImg" class="acm-img border1rc" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]">
                    </div>
                </div>
                <div class="col-8">
                    <div>
                            <p>Tipo de recurso: {{itemData.resource.tipo_valor}}</p>
                            <h3>{{itemData.resource.name}}</h3>
                            <p>Contiene: {{itemData.media.length}} archivos adjuntos</p>
                    </div>
                    <div>
                        <button class="btn btn-default btn-sm float-right mr-2" 
                            v-if="has_cap('crear-promociones')"
                            @click="onPromo"><i class="fas fa-star"></i> Promocionar</button>                        
                        <button class="btn btn-default btn-sm float-right mr-2" 
                            v-if="has_cap('editar-recursos') || itemData.resource.creator_id === acAppData.current_user.id"
                            @click="onEdit">
                            <i class="fas fa-pen"></i>  Editar</button>
                        <button class="btn btn-default btn-sm float-right mr-2" 
                            v-if="has_cap('eliminar-recursos') || itemData.resource.creator_id === acAppData.current_user.id"
                            @click="onDelete">
                            <i class="fas fa-trash-alt"></i>  Eliminar</button>                        
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
                            <a v-if="isPDF(e.name)" @click="showPDF(e.url)" href="javascript:void(0);" class="btn btn-default btn-sm float-right mr-2" download>
                                <i class="fas fa-book-reader"></i> Visualizar</a>
                        </span>

                    </div>
                </li>
            </ul>

        <div class="ac_frame-pdf-preview" v-if="pdfselected != null">
            <div class="ac_frame-header">
                <button class="ac_close" @click="onClosePDF">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <object class="ac_frame-pdf-target" :data="pdfselected" type="application/pdf">
                <div>No online PDF viewer installed</div>
            </object>
        </div>

        </div>
        </div>
    </div>
</template>

<script>
import {deleteResource} from '@/service';
export default {
    props: {
        pdata: {type: Object,required:true}
    },  
    data: function(){
        return {
            isDeleting: false,
            tiposRecursos: [],
            pdfselected: null,
            acAppData: window.obj_ac_app,
            itemData: JSON.parse(JSON.stringify(this.pdata))            
        }
    },
    created: function(){    
    },
    computed: {
        srcPresentationImg: function(){
            if(this.itemData.presentation_model == null){
                return this.acAppData.base_url + "/images/default_book.jpg";
            }else{
                return this.itemData.presentation_model.url;
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
            this.$emit('on-edit',this.itemData.resource.id);
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
        showPDF: function(pdf_url){
            document.body.classList.add('no-scroll');
            this.pdfselected = pdf_url;            
        },

        onPresentationImg: function(){
            if(this.itemData.presentation_model != null){
                this.onSources(this.itemData.presentation_model);
            }
        },
        onSources: function(target){
            const object_media = {
                items: [],
                target: null
            }

            object_media.target = target;
            if(this.itemData.presentation_model != null){
                object_media.items.push(this.itemData.presentation_model);
            }            

            this.$emit('source-files',object_media);
        },
        onClosePDF: function(){
            document.body.classList.remove('no-scroll');
            this.pdfselected = null;
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

    .ac_frame-pdf-preview{
        display: flex;
        position: fixed;
        z-index: 1060;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: #262323a3;
        flex-direction: column;
        animation: fadeIn 0.5s; 
    }

    .ac_frame-pdf-target{
        width: 100%;
        max-width: 950px;
        height: 100%;
        display: block;
        margin: auto;
    }

    .ac_frame-header{
        background-color: #ccc8c8;
    }

    .ac_frame-header .ac_close{
        background-color: #ca7373;
        padding: 5px 20px;
        float: right;
        border: 0px;
        color: white;
    }



</style>