<template>
  <div>
    <div
      style="margin-bottom: 15px; max-height: 200px"
      class="row overflow-auto"
    >
      <div
        v-for="(m, key) in media"
        v-bind:key="key"
        class="col-6 col-lg-3 col-md-3"
      >
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
              href="#"
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
              href="#"
              style="display: inline"
              >&#215;</a
            >
          </div>
        </div>
        <div v-if="m.type === 'docfile'">
          <div
            class="image-area"
            data-toggle="tooltip"
            data-placement="top"
            :title="m.filename"
          >
            <iframe
              :ref="'image' + key"
              width="100%"
              style="object-fit: cover; padding-top: 3px"
              height="100px"
              frameborder="0"
              :src="m.data"
              scrolling="no"
            ></iframe>
            <a
              @click="remove(key)"
              class="remove-image"
              href="#"
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
<script>
export default {
  data: function(){
    return {
      media: [],
      link_youtube: "",
      show_modal: false
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
      this.addFileToMultimedia(e.target.files[0]);
    },
    addFileToMultimedia: function(file){
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (e) => {
        if (e.target.result.substring(0, 10) != "data:image" && e.target.result.substring(0, 20) != "data:application/pdf") {
          StatusHandler.ValidationMsg("No se permiten archivos diferentes");
          return 0;
        }

        console.log(e.target.result.substring(0, 10));
        console.log(e.target.result.substring(0, 20));

        var data = {
          type:  e.target.result.substring(0, 10) == "data:image"? "image" : "docfile",
          filename: file.name,
          data: e.target.result,
        };
        this.media.push(data);
        this.$emit("media", this.media);
      };      
    },
    remove: function (key) {
      this.media.splice(key, 1);
      console.log("hey");
    },
    setMediaNull: function () {
      this.media = [];
    },
    addVideo: function (video) {
      const data = {
        type: "video",
        data: video,
      };
      this.media.push(data);
      this.$emit("media", this.media);
    }            
  }  
}
</script>