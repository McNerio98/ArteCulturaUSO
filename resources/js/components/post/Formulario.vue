<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12 titleContainer">
          <img
            width="60px"
            height="60px"
            style="object-fit:cover"
            class="img-circle img-bordered-sm"
            src="images/pintora.jpg"
            alt="user image"
          />
          <div style="width:100%; margin-left: 10px;" class="form-group">
            <input
              v-model="titulo"
              placeholder="Titulo de la publicacion"
              width="100%"
              type="email"
              class="form-control"
              id="exampleInputEmail1"
              aria-describedby="emailHelp"
            />
          </div>
        </div>
        <div class="col-12">
          <div class="form-group" v-if="eventType == 'true'">
            <label for="exampleFormControlTextarea1" class="text-muted">Fecha del Evento</label>
            <input
              class="form-control"
              type="date"
              id="start"
              name="trip-start"
              value="2018-07-22"
              min="2018-01-01"
              max="2018-12-31"
            />
          </div>
          <div class="form-group">
            <label for="exampleFormControlTextarea1"></label>
            <textarea
              v-model="descripcion"
              style="resize:none"
              class="form-control"
              v-bind:placeholder="props.messagePlaceholder"
              id="exampleFormControlTextarea1"
              rows="3"
            ></textarea>
          </div>
        </div>
        <div class="col-12">
          <postMedia-component @media="setListMedia"></postMedia-component>
        </div>
        <div class="col-12">
          <button @click="publicarContent" class="btn-danger">Publicar</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
const { util } = require('../../api/api.service')
export default {
  props: ["username", "eventType"],
  data() {
    return {
      props: {
        username: this.username,
        eventType: this.eventType,
        messagePlaceholder:
          this.eventType === "true"
            ? "Describe el evento..."
            : "¿Que estas pensado, " + this.username + "?",
      },
      multimedia: [],
      titulo: "",
      descripcion: "",
    };
  },
  methods: {
    setListMedia(media) {
      this.multimedia = media;
    },
    publicarContent() {
      if (this.titulo === "" || this.descripcion === "") {
          util("error", "La publicacion debe tener titulo y descripcion");
      } else {
        const data = {
          titulo: this.titulo,
          descripcion: this.descripcion,
          ...this.multimedia,
        };
        console.log(data);
      }
    },
  },
};
</script>
