<template>
  <div>
    <div style="margin-bottom:15px" class="row" id="displayImages">
      <div v-for="(m, key) in media" v-bind:key="key" class="col-3">
        <div v-if="m.type === 'image'" id="content">
          <div class="image-area" data-toggle="tooltip" data-placement="top" :title="m.filename">
            <img
              :ref="'image' + key"
              style="object-fit:contain; padding-top:3px"
              width="100%"
              height="100px"
              :src="m.data"
              alt="Preview"
            />
            <a @click="remove(key)" class="remove-image" href="#" style="display: inline;">&#215;</a>
          </div>
        </div>
        <div v-if="m.type === 'video'" id="content">
          <div class="image-area">
            <iframe
              data-toggle="tooltip" data-placement="top" :title="m.filename"
              :ref="'image' + key"
              width="100%"
              style="object-fit:contain; padding-top:3px"
              height="100px"
              frameborder="0"
              :src="'https://www.youtube.com/embed/' + m.data.substring(m.data.length,32)"
            ></iframe>
            <a @click="remove(key)" class="remove-image" href="#" style="display: inline;">&#215;</a>
          </div>
        </div>
        <div v-if="m.type === 'docfile'" id="content">
          <div class="image-area">
            <iframe
              data-toggle="tooltip" data-placement="top" :title="m.filename"
              :ref="'image' + key"
              width="100%"
              style="object-fit:cover; padding-top:3px"
              height="100px"
              frameborder="0"
              :src="m.data"
              scrolling="no"
            ></iframe>
            <a @click="remove(key)" class="remove-image" href="#" style="display: inline;">&#215;</a>
          </div>
        </div>
      </div>

      <div v-if="media.length  > 0" class="col-3">
        <label for="imageInput">
          <div id="content">
            <div class="image-area">
              <img
                style="object-fit:contain; padding-top:3px;cursor:pointer"
                width="100%"
                height="100px"
                src="https://img.icons8.com/dotty/2x/add-image.png"
                alt="Preview"
              />
            </div>
          </div>
        </label>
      </div>
    </div>
    <label for="imageInput" style="cursor:pointer" class="badge badge-light">
      <img
        src="https://winaero.com/blog/wp-content/uploads/2019/11/Photos-new-icon.png"
        width="20px"
        height="20px"
        style="object-fit:cover"
      />
      Fotos
    </label>
    <input
      accept="image/*"
      hidden="true"
      id="imageInput"
      type="file"
      @change="previewFiles"
    />
    <span
      style="cursor:pointer"
      id="btn-video-media"
      class="badge badge-light"
      data-toggle="modal"
      data-target="#staticBackdrop"
    >
      <img
        src="https://www.freeiconspng.com/thumbs/video-icon/video-icon-1.png"
        width="20px"
        height="20px"
        style="object-fit:cover"
      />
      Videos
    </span>
    <label for="contenidoInput" style="cursor:pointer" class="badge badge-light">
      <img
        src="https://cdn.iconscout.com/icon/free/png-512/my-files-1-461722.png"
        width="20px"
        height="20px"
        style="object-fit:cover"
      />
      Contenido(Pdf)
    </label>
    <input hidden="true" id="contenidoInput" accept = "application/pdf" type="file" @change="previewFiles" multiple />
    <!-- Modal -->
    <div
      class="modal fade"
      id="staticBackdrop"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      ref="modalforVideo"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-blue">
            <h5 class="modal-title" id="exampleModalLabel">Agregar video</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5 class="text-muted">Enlaza tu video de youtube copiando el link del video.</h5>
            <form id="formData">
              <div class="form-group">
                <input
                  v-model="linkYoutube"
                  placeholder="Ex. https://www.youtube.com/watch?v=Pu8LH6r-wOU"
                  type="text"
                  class="form-control is-invalid"
                  aria-describedby="emailHelp"
                  required
                />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button @click="addVideo()" v-if="linkYoutube" type="button" class="btn btn-primary">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as $$ from "jquery";
import { util } from '../../api/api.service';
export default {
  data() {
    return {
      media: [],
      props: {},
      linkYoutube: "",
    };
  },
  methods: {
    previewFiles(e) {
      var instance = this;
      var reader = new FileReader();
      let filename = e.target.files[0].name;
      reader.onload = function (event) {
        if(reader.result.substring(0,10) != "data:image" && reader.result.substring(0,20) != "data:application/pdf"){
            util("error", "No se permiten archivos diferentes");
            return 0;
        }
        const data = {
          type: (reader.result.substring(0,10) == "data:image" ? "image" : "docfile" ),
          filename: filename,
          data: reader.result,
        };
        instance.media.push(data);
        instance.$emit("media", instance.media)//<-- Si me preguntas, esto hace comunicacion con el cp padre
      };
      reader.readAsDataURL(e.target.files[0]);
    },
    remove(key) {
      this.media.splice(key, 1);
      console.log("hey");
    },
    addVideo() {
      const data = {
        type: "video",
        data: this.linkYoutube,
      };
      this.media.push(data)
      this.$emit("media", this.media)
      document.getElementById("btn-video-media").click();
      this.linkYoutube = ""
    },
  },
};
</script>
