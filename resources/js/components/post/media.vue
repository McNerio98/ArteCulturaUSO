<template>
  <div>
    <div style="margin-bottom: 15px; max-height: 200px" class="row overflow-auto">
      <!--LISTA PRINCIPAL DE IMAGENES O VIDEOS-->
      <div v-for="(m, key) in media" v-bind:key="key" class="col-6 col-lg-3 col-md-3">
        
        <div v-if="m.type === 'image'">
          <div class="image-area"
            data-toggle="tooltip"
            data-placement="top"
            :title="m.filename">
            <img
              :ref="'image' + key"
              style="object-fit: contain; padding-top: 3px"
              width="100%"
              height="100px"
              :src="m.data"
              alt="Preview"
            />
            <a
              @click="remove(key,m.id)"
              class="remove-image"
              href="javascript:void(0);"
              style="display: inline"
              >&#215;</a
            >
          </div>
        </div>

        <div v-if="m.type === 'video'">
          <div
            class="image-area"
            data-toggle="tooltip"
            data-placement="top"
            :title="m.filename"
          >
            <a :href="'https://youtu.be/'+m.filename" target="_blank">
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
              @click="remove(key,m.id)"
              class="remove-image"
              href="javascript:void(0);"
              style="display: inline"
              >&#215;</a
            >
          </div>
        </div>
      </div>
      <!--BOTON 0 PARA AGREGAR IMAGENES-->
      <div v-if="media.length > 0" class="col-6 col-lg-3 col-md-3">
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
        <li v-for="(m, key) in media_docs" v-bind:key="key" class="docfile mb-2" :title="m.filename">
          <a :href="buffer.edit_mode ? m.data : 'javascript:void(0);'" target="_blank" class="btn-link text-secondary"><i class="far fa-file-pdf"></i> {{m.filename}}</a>
          <a
            @click="removeDocs(key,m.id)"
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
          @change="this.previewFiles"
          multiple
        />
      </div>
      <div class="col-4">
        <label
          style="cursor: pointer"
          id="btn-video-media"
          class="btn btn-light btn-block"
          @click="show_modal = true"
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
          @click="this.triggerInputForDocs"
          style="cursor: pointer"
          class="btn btn-light btn-block text-break"
        >
          <img
           :src="acAppData.base_url + '/images/iconBtnAddDoc.png'"
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
          @change="previewFiles"
          multiple
        />
      </div>
    </div>
    <!--VIDEO MODAL COMPONENT-->
    <post-modal-component
      @add="addVideo"
      v-if="show_modal"
      @close="show_modal = false"
    ></post-modal-component>
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
export default {
    props: {
        buffer: {type: Object,default: function(){
          return {
            edit_mode: false,
            medias: [] //all video, docs and files 
          }
        }}                
    },  
  data: function () {
    return {
      acAppData: {},
      media: [],
      media_docs: [],
      link_youtube: "",
      show_modal: false,
      limite: 70, //limite de archivos,
      media_del: [] //Ids de elementos a eliminar en modo edicion 
    };
  },
  created: function () {
    //Cuando el padre emita el evento update se limpian lo medios
    this.$parent.$on("post-chiild-created", this.setMediaNull);
  },
  mounted: function(){
    this.acAppData = window.obj_ac_app;
    
    if(this.buffer.edit_mode){
      this.buffer.medias.map(e=>{
          var temp = {
              id: e.id,
              type: e.type_file,
              filename: e.name,
              data: e.url
            };

          if(e.type_file=="image" || e.type_file == "video")this.media.push(temp);
          if(e.type_file =="docfile")this.media_docs.push(temp);     
      })
    }
  },
  methods: {
    triggerInputForImages: function () {
      this.$refs.inputforimgs.click();
    },
    triggerInputForDocs: function () {
      this.$refs.inputfordocs.click();
    },
    previewFiles: function (e) {

      if((this.media.length + this.media_docs.length + e.target.files.length) >= this.limite){
        StatusHandler.ValidationMsg("Límite de carga de archivos superado, elimine algunos elementos.")
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

        //console.log(e.target.result.substring(0, 10));
        //console.log(e.target.result.substring(0, 20));

        var data = {
          id: null,
          type: e.target.result.substring(0, 10) == "data:image" ? "image" : "docfile",
          filename: file.name,
          data: e.target.result,
        };

        if(data.type === "docfile"){
          this.media_docs.push(data);
        }else{
          this.media.push(data);
        }

        this.$emit("media", this.media.concat(this.media_docs));
      };
    },
    remove: function (key,id = null) {
      this.media.splice(key, 1);
      //Si esta en modo edicion y el id pasado es de un archivo existente (ya almacenado)
      //entonces agregar el id a los medios a eliminar y emitir evento con lista de identificadores 
      if(id != null && id != 0 && this.buffer.edit_mode){
          this.media_del.push(id);
          this.$emit("media-del",this.media_del);
      }      
    },
    removeDocs: function(key,id=null){
      this.media_docs.splice(key,1);
      if(id != null && id != 0 && this.buffer.edit_mode){
          this.media_del.push(id);
          this.$emit("media-del",this.media_del);
      }
    },
    setMediaNull: function () {
      this.media = [];
      this.media_docs = [];
    },
    addVideo: function (video_uri) {
      if((this.media.length + this.media_docs.length + 1) >= this.limite){
        StatusHandler.ValidationMsg("Límite de carga de archivos superado, elimine algunos elementos.")
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

      const data = {
        id: null,
        type: "video",
        filename: id_video,
        data: id_video,//vendria siendo el path completo 
      };
      this.media.push(data);
      this.$emit("media", this.media.concat(this.media_docs));
    },
  },
};
</script>