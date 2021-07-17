<template>
  <div>
    <div
      style="margin-bottom: 15px; max-height: 200px"
      class="row overflow-auto"
    >
      <div v-for="(m, key) in media" v-bind:key="key" class="col-6 col-lg-3 col-md-3">
        
        <div v-if="m.type === 'image'">
          <div
            class="image-area"
            data-toggle="tooltip"
            data-placement="top"
            :title="m.filename"
          >
            <img
              :ref="'image' + key"
              style="object-fit: contain; padding-top: 3px"
              width="100%"
              height="100px"
              :src="m.data"
              alt="Preview"
            />
            <a
              @click="remove(key)"
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
            <iframe
              :ref="'image' + key"
              width="100%"
              style="object-fit: contain; padding-top: 3px"
              height="100px"
              frameborder="0"
              :src="
                'https://www.youtube.com/embed/' +
                m.data.substring(m.data.length, 32)
              "
            ></iframe>
            <a
              @click="remove(key)"
              class="remove-image"
              href="javascript:void(0);"
              style="display: inline"
              >&#215;</a
            >
          </div>
        </div>
      </div>

      <div v-if="media.length > 0" class="col-6 col-lg-3 col-md-3">
        <label for="imageInput" @click="this.triggerInputForImages">
          <div id="content">
            <div class="image-area2">
              <img
                style="object-fit: contain; cursor: pointer"
                width="80px"
                height="80px"
                src="https://icon-library.com/images/add-camera-icon/add-camera-icon-25.jpg"
                alt="Preview"
              />
            </div>
          </div>
        </label>
      </div>
    </div>

    <ul v-for="(m, key) in media_docs" v-bind:key="key" class="list-unstyled mb-2">
        <li v-if="m.type === 'docfile'" class="docfile" :title="m.filename">
        <a href="" class="btn-link text-secondary"><i class="far fa-file-pdf"></i> {{m.filename}}</a>
        <a
          @click="remove(key)"
          class="remove-image alter-remove"
          href="javascript:void(0);"
          style="display: inline">
          &#215;
        </a>          
        </li>
    </ul>

    <div class="row">
      <div class="col-4">
        <label
          for="imageInput"
          style="cursor: pointer"
          class="btn btn-light btn-block"
          @click="this.triggerInputForImages"
        >
          <img
            src="https://winaero.com/blog/wp-content/uploads/2019/11/Photos-new-icon.png"
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
            src="https://www.freeiconspng.com/thumbs/video-icon/video-icon-1.png"
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
            src="https://cdn.iconscout.com/icon/free/png-512/my-files-1-461722.png"
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
  data: function () {
    return {
      media: [],
      media_docs: [],
      link_youtube: "",
      show_modal: false,
      limite: 5, //limite de archivos 
    };
  },
  created: function () {
    this.$parent.$on("update", this.setMediaNull);
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

console.log("Target");
console.log(e);
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
          type:
            e.target.result.substring(0, 10) == "data:image"
              ? "image"
              : "docfile",
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
    remove: function (key) {
      this.media.splice(key, 1);
    },
    setMediaNull: function () {
      this.media = [];
    },
    addVideo: function (video) {
      if((this.media.length + this.media_docs.length + 1) >= this.limite){
        StatusHandler.ValidationMsg("Límite de carga de archivos superado, elimine algunos elementos.")
        return;
      }

      const data = {
        type: "video",
        data: video,
      };
      this.media.push(data);
      this.$emit("media", this.media.concat(this.media_docs));
    },
  },
};
</script>