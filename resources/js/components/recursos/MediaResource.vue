<template>
    <div>
        <div style="margin-bottom: 15px; max-height: 200px" class="row overflow-auto">
            <div v-for="(m, key) of ListImagesOrVideos" v-bind:key="key" class="col-6 col-lg-3 col-md-3">
                <div v-if="m.type_file === 'image'">
                    <div class="image-area"
                        data-toggle="tooltip"
                        data-placement="top"
                        :title="m.name">
                        <img style="object-fit: contain; padding-top: 3px"
                            width="100%"
                            height="100px"
                            :src="m.data != null ? m.data : m.url"
                        alt="Preview"/>
                        <a @click="removeFile(m.index_parent,m.id)"
                            class="remove-image"
                            href="javascript:void(0);"
                            style="display: inline">
                            &#215;
                        </a>
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

                        <a @click="removeFile(m.index_parent,m.id)"
                            class="remove-image"
                            href="javascript:void(0);"
                            style="display: inline">
                                &#215;
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <!--SECCION PARA MOSTRAR SOLO LOS DOCUMENTOS-->
        <ul class="list-unstyled">
            <li v-for="(m, key) of ListDocs" v-bind:key="key" class="docfile mb-2" :title="m.name">
                <a target="_blank" class="btn-link text-secondary"><i class="far fa-file-pdf"></i> {{m.name}}</a>
                <a class="remove-image alter-remove"
                    href="javascript:void(0);"
                    @click="removeDocs(m.index_parent,m.id)"
                    style="display: inline">
                    &#215;
                </a>                  
            </li>
        </ul>    

        <!--OPCIONES PARA CARGAR ARCHIVOS MULTIMEDIA-->
        <div class="row">
            
            <!-- <div class="col-4">
                <label for="imageInput"
                    style="cursor: pointer"
                    class="btn btn-light btn-block"
                @click="triggerInputForImages">
                <img :src="acAppData.base_url + '/images/iconBtnAddImg.png'"
                    class="img-fluid"
                    width="20px"
                    height="20px"
                style="object-fit: cover"/>
                Fotos
                </label>
                <input accept="image/*"
                    hidden="true"
                    type="file"
                    ref="inputforimgs"
                    @change="addFile"
                multiple/>
            </div>
            -->

            <div class="col-6">
                <label for="contenidoInput"
                    @click="triggerInputForMSWord"
                    style="cursor: pointer"
                class="btn btn-light btn-block text-break">
                <img :src="acAppData.base_url + '/images/icons/wordfile.png'"
                    width="20px"
                    height="20px"
                style="object-fit: cover"/>
               Document Word
                </label>
                <input hidden="true"
                    accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                    ref="inputforword"
                    type="file"
                    @change="addFile"
                multiple />
            </div>              

            <div class="col-6">
                <label for="contenidoInput"
                    @click="triggerInputForDocs"
                    style="cursor: pointer"
                class="btn btn-light btn-block text-break">
                <img :src="acAppData.base_url + '/images/icons/pdffile.png'"
                    width="20px"
                    height="20px"
                style="object-fit: cover"/>
                Documento PDF
                </label>
                <input hidden="true"
                    accept="application/pdf"
                    ref="inputfordocs"
                    type="file"
                    @change="addFile"
                multiple />
            </div>  


        </div>        
        <ModalVideo @add="addVideo"
            v-if="flags.modal_video_youtube"
            @close="flags.modal_video_youtube = false">
        </ModalVideo>        
    </div>
</template>

<style scoped>
    .remove-image {
    display: none;
    position: absolute;
    top: -10px;
    right: -10px;
    border-radius: 10em;
    padding: 2px 6px 3px;
    text-decoration: none;
    font: 700 18px/17px sans-serif;
    background: #555;
    border: 3px solid #fff;
    color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5), inset 0 2px 4px rgba(0, 0, 0, 0.3);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    -webkit-transition: background 0.5s;
    transition: background 0.5s;
    }

    .remove-image.alter-remove{
    top: auto !important;
    }

    .remove-image:hover {
    background: #e54e4e;
    border: 3px solid #fff;
    color: #fff;
    }
    .remove-image:active {
    background: #e54e4e;
    top: -10px;
    right: -11px;
    }

    li.docfile {
    background-color: #f0f0f0;
    padding: 5px 0;
    overflow: hidden;
    border-radius: 10px;
    }

    li.docfile i{
    font-size: 150%;  
    }

    .image-area {
    position: relative;
    background: rgb(197, 195, 195);
    width: 100%;
    display: flex;
    border: 5px solid rgb(235, 230, 230);
    height: 115px;
    }

    .image-area2 {
    position: relative;
    background: rgb(197, 195, 195);
    width: 115px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 5px solid rgb(235, 230, 230);
    height: 115px;
    border-radius: 50%;
    }

</style>

<script>
/**
 * mario.nerio
 * Este componente fue copiado desde el mismo componente de Biografias
 * sew realizaron leves cambios 
 */
    import ModalVideo from '../post/ModalVideo.vue';
    export default {
        components: {ModalVideo},
        props: {
            itemData: {type: Object,required: true},
        },
        data: function(){
            return {
                acAppData: window.obj_ac_app,
                limitefiles: 10,
                mediadrop_ids: [],
                flags: {
                    modal_video_youtube: false
                }
            }
        },
        mounted: function(){
            
        },
        computed: {
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
        methods: {
            triggerInputForImages: function(){
                this.$refs.inputforimgs.click();
            },
            triggerInputForMSWord: function(){
                this.$refs.inputforword.click();
            },
            triggerInputForDocs: function(){
                this.$refs.inputfordocs.click();
            },

            removeFile: function(indexParent,id){
                this.itemData.media.splice(indexParent,1);
                //For edit mode 
                if(id != 0){this.mediadrop_ids.push(id);}
                this.$emit("drop-ids",this.mediadrop_ids);
            },
            removeDocs: function(indexParent,id){
                this.itemData.media.splice(indexParent,1);
            },
            addFile: function(e){
                if((this.itemData.media.length + e.target.files.length) >= this.limitefiles){
                    StatusHandler.ValidationMsg("Límite de carga de archivos superado, elimine algunos elementos.")
                    return;
                }

                for(let ng = 0 ; ng < e.target.files.length; ng++){
                    this.addFileToMultimedia(e.target.files[ng]);
                }                

            },
            addFileToMultimedia: function(file){
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    const validExten = ["pdf","doc","docx","jpeg","jpg","png"];
                    let extenstion = file.name.substring(file.name.lastIndexOf('.')+1, file.name.length) || null;

                    if(extenstion == null || !validExten.includes(extenstion.toLowerCase().trim())){
                         StatusHandler.ValidationMsg("Archivos no soportados");
                         return;
                    }

                    var newFileMedia = {
                        id: null,
                        type_file: e.target.result.substring(0, 10) == "data:image" ? "image" : "docfile",
                        name: file.name,
                        memory_id: null, //se establece en el padre
                        data: e.target.result,
                    };

                    this.itemData.media.push(newFileMedia);                    
                }
            }            
        }
    }
</script>
