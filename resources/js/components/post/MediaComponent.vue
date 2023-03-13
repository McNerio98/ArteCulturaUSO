<template>
  <div>
    <div style="margin-bottom: 15px; max-height: 200px" class="row overflow-auto">
      <!--LISTA PRINCIPAL DE IMAGENES O VIDEOS-->
      <div v-for="(m, key) in ListImagesOrVideos" v-bind:key="key" class="col-6 col-lg-3 col-md-3">
        
        <div v-if="m.type_file === 'image'">
          <div class="image-area"
            data-toggle="tooltip"
            data-placement="top"
            :title="m.name">
            <img
              style="object-fit: contain; padding-top: 3px"
              width="100%"
              height="100px"
              :src="m.data != null ? m.data : m.url"
              alt="Preview"
            />
            <a
              @click="removeFile(m.index_parent,m.id)"
              class="remove-image"
              href="javascript:void(0);"
              style="display: inline"
              >&#215;</a
            >
          </div>
        </div>

        <div v-if="m.type_file === 'video'">
          <div
            class="image-area"
            data-toggle="tooltip"
            data-placement="top"
            :title="m.name"
          >
            <a :href="'https://youtu.be/'+m.name" target="_blank">
            <img
              :ref="'image' + key"
              style="object-fit: contain; padding-top: 3px"
              width="100%"
              height="100px"
              :src="acAppData.base_url + '/images/youtube_media_cmp.jpg'"
              alt="Preview"
            />
            </a>

            <a
              @click="removeFile(m.index_parent,m.id)"
              class="remove-image"
              href="javascript:void(0);"
              style="display: inline"
              >&#215;</a
            >
          </div>
        </div>
      </div>
      <!--BOTON 0 PARA AGREGAR IMAGENES-->
      <div v-if="ListImagesOrVideos.length > 0" class="col-6 col-lg-3 col-md-3">
        <label for="imageInput" @click="this.triggerInputForImages">
          <div id="content">
            <div class="image-area2">
              <img
                style="object-fit: contain; cursor: pointer"
                width="80px"
                height="80px"
                :src="acAppData.base_url + '/images/iconBtnAddImg2.png'"
                alt="Preview"
              />
            </div>
          </div>
        </label>
      </div>
    </div>

    <!--SECCION PARA MOSTRAR SOLO LOS DOCUMENTOS-->
    <ul class="list-unstyled">
        <li v-for="(m, key) in ListDocs" v-bind:key="key" class="docfile mb-2" :title="m.filename">
          <a :href="m.url != null ? m.url : 'javascript:void(0);'" :target="m.url != null ? '_blank':false" class="btn-link text-secondary"><i class="far fa-file-pdf"></i> {{m.name}}</a>
          <a
            @click="removeDocs(m.index_parent,m.id)"
            class="remove-image alter-remove"
            href="javascript:void(0);"
            style="display: inline">
            &#215;
          </a>          
        </li>
    </ul>

    <!--BOTONES DE AGREGAR FOTO / VIDEO / ARCHIVO-->
    <div class="row">
      <div class="col-4">
        <label
          for="imageInput"
          style="cursor: pointer"
          class="btn btn-light btn-block"
          @click="this.triggerInputForImages"
        >
          <img
            :src="acAppData.base_url + '/images/iconBtnAddImg.png'"
            class="img-fluid"
            width="20px"
            height="20px"
            style="object-fit: cover"
          />
          Fotos
        </label>
        <input
          accept="image/*"
          hidden="true"
          type="file"
          ref="inputforimgs"
          @change="addFile"
          multiple
        />
      </div>
      <div class="col-4">
        <label
          style="cursor: pointer"
          id="btn-video-media"
          class="btn btn-light btn-block"
          @click="flags.modal_video_youtube = true"
        >
          <img
            :src="acAppData.base_url + '/images/iconBtnAddVideo.png'"
            class="img-fluid"
            width="20px"
            height="20px"
            style="object-fit: cover"
          />
          Videos
        </label>
      </div>
      <div class="col-4">
        <label
          for="contenidoInput"
          @click="triggerInputForDocs"
          style="cursor: pointer"
          class="btn btn-light btn-block text-break"
        >
          <img
           :src="acAppData.base_url + '/images/icons/pdffile.png'"
            width="20px"
            height="20px"
            style="object-fit: cover"
          />
          Archivos
        </label>
        <input
          hidden="true"
          accept="application/pdf"
          ref="inputfordocs"
          type="file"
          @change="addFile"
          multiple
        />
      </div>
    </div>
    <!--VIDEO MODAL COMPONENT-->
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
import ModalVideo from './ModalVideo.vue';

export default {
  components: {ModalVideo},
    props: {
      itemData: {type: Object, required:true}
    },  
  data: function () {
    return {
      acAppData: {},
      limitefiles: 10,
      mediadrop_ids: [],
      limitFileName: 200,
      flags: {
          modal_video_youtube: false
      }      
    };
  },
  created: function () {
    //Cuando el padre emita el evento update se limpian lo medios
    //this.$parent.$on("post-chiild-created", this.setMediaNull);
  },
  mounted: function(){
    this.acAppData = window.obj_ac_app;
    
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
    triggerInputForImages: function () {
      this.$refs.inputforimgs.click();
    },
    triggerInputForDocs: function () {
      this.$refs.inputfordocs.click();
    },
    addVideo: function(video_uri){
      if((this.itemData.media.length + 1) > this.limitefiles){
        StatusHandler.ValidationMsg(`Límite de carga de archivos superado, elimine algunos elementos. (Limite : ${this.limitefiles} archivos)`);
        return;
      }

      if(!video_uri.includes('youtu')){
        StatusHandler.ValidationMsg("Debe cargar un video desde YOUTUBE.")
        return;        
      }

      var mtx = video_uri.split('/');
      var target = mtx[mtx.length -1 ];
      var id_video = "";

      //Aplicar algoritmo de extraccion de id para url full 
      if(video_uri.includes('watch')){
        id_video = target.split('=')[1].split('&')[0];
      }

      const newVideoMedia = {
        id: 0,
        type_file: "video",
        name: id_video,
        id_post_event: null,
        data: id_video,//vendria siendo el path completo 
      };        

      this.itemData.media.push(newVideoMedia);
    },
    addFile: function(e){
      console.log((this.itemData.media.length + e.target.files.length) > this.limitefiles);
      if((this.itemData.media.length + e.target.files.length) > this.limitefiles){
        StatusHandler.ValidationMsg(`Límite de carga de archivos superado, elimine algunos elementos. (Limite : ${this.limitefiles} archivos)`);
        return;
      }

      for(let ng = 0 ; ng < e.target.files.length; ng++){
        this.addFileToMultimedia(e.target.files[ng]);
      }                
    },    
    addFileToMultimedia: function (file) {

      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (e) => {
        if (e.target.result.substring(0, 10) != "data:image" && e.target.result.substring(0, 20) != "data:application/pdf") {
          StatusHandler.ValidationMsg("Archivos no soportados");
          return;
        }

        const file_size = ((file.size / 1024) / 1024); //En Megas 
        const limit_config = parseInt(this.get_param('FILE_SIZE',20));
        console.log('Filze Size: ' + file_size);
        if(file_size > limit_config){
          StatusHandler.ValidationMsg(`Tamaño del archivo muy grande, límite configurado ${limit_config}`);
          return;                        
        }

        var newFileMedia = {
            id: 0,
            type_file: e.target.result.substring(0, 10) == "data:image" ? "image" : "docfile",
            name: file.name,
            id_post_event: null, //se establece en el backend
            data: e.target.result,
        };

        if(file.name.length > this.limitFileName && newFileMedia.type_file == "docfile"){
          StatusHandler.ValidationMsg(`El nombre del documento supera el límite de ${this.limitFileName} caracteres`);
        }else{
          this.itemData.media.push(newFileMedia);    
        }
      };
    },
    removeFile: function (indexParent,id) {
      this.itemData.media.splice(indexParent,1);
      //For edit mode 
      if(id != 0){this.mediadrop_ids.push(id);}
      this.$emit("drop-ids",this.mediadrop_ids);
    },
    removeDocs: function(indexParent,id){
      this.itemData.media.splice(indexParent,1);
      if(id != 0){this.mediadrop_ids.push(id);}
      this.$emit("drop-ids",this.mediadrop_ids);
    },
    get_param(key_param,defvalue){
      return window.get_param == undefined ? defvalue : window.get_param(key_param,defvalue);
    }        
  },
};
</script>