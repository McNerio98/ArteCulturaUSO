<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12 titleContainer">
          <img
            width="60px"
            height="60px"
            style="object-fit: cover"
            class="img-circle img-bordered-sm"
            src="images/pintora.jpg"
            alt="user image"
          />
          <div style="width: 100%; margin-left: 10px" class="form-group">
            <input
              v-model="titulo"
              v-bind:placeholder="props.messagePlaceholderTitulo"
              width="100%"
              type="email"
              class="form-control"
              id="exampleInputEmail1"
              aria-describedby="emailHelp"
            />
          </div>
        </div>
        <div class="col-12">
            <br/>
          <div class="row">
            <div class="col-12 col-lg-4 col-md-4">
              <div class="form-group" v-if="eventType == 'true'">
                <label for="exampleFormControlTextarea1" class="text-muted"
                  >Fecha/hora</label
                >
                <input
                ref="date"
                  class="form-control"
                  type="datetime-local"
                  id="start"
                  name="trip-start"
                  max="2030-12-31T00:00"
                  v-model="dateevent"
                />
              </div>
            </div>
            <div class="col-12 col-lg-4 col-md-4">
              <div class="form-group" v-if="eventType == 'true'">
                <label for="exampleFormControlTextarea1" class="text-muted"
                  >Tipo de evento</label
                >
                <select v-model="tipoevento" class="custom-select">
                  <option selected value="1">Eventual</option>
                  <option value="2">Permanente</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4 col-md-4">
              <div class="form-group" v-if="eventType == 'true'">
                <label for="exampleFormControlTextarea1" class="text-muted"
                  >Categoria</label
                >
                <select
                  id="selectPrice"
                  @change="openPriceModal"
                  class="custom-select"
                >
                  <option selected value="1">Gratuito</option>
                  <option value="2">Pagado</option>
                </select>
              </div>
            </div>
            <div v-if="showElement" class="col-12 col-lg-4 col-md-4">
              <div class="form-group">
                <label
                  for="exampleFormControlTextarea1"
                  class="text-muted"
                  data-toggle="tooltip"
                  data-placement="right"
                  title="Informa a los asistentes que tu evento tendra un valor definido"
                  >Digite el precio
                  <svg
                    data-toggle="tooltip"
                    data-placement="right"
                    title="Informa a los asistentes que tu evento tendra un valor especificado"
                    width="1em"
                    height="1em"
                    viewBox="0 0 16 16"
                    class="bi bi-info-circle-fill"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"
                    />
                  </svg>
                </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">$</span>
                  </div>
                  <input
                    v-model="eventprice"
                    type="number"
                    min="0"
                    class="form-control"
                    placeholder="precio"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                    required
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <label for="exampleFormControlTextarea1"></label>
            <textarea
              v-model="descripcion"
              style="resize: none"
              class="form-control"
              v-bind:placeholder="props.messagePlaceholderArea"
              id="exampleFormControlTextarea1"
              rows="3"
            ></textarea>
          </div>
        </div>
        <div class="col-12">
          <postMedia-component @media="setListMedia"></postMedia-component>
        </div>
        <div class="col-12">
            <br/>
          <button type="button" @click="publicarContent" class="btn btn-success">Publicar</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as $$ from "jquery";
const { util } = require("../../api/api.service");
export default {
  props: ["username", "eventType"],
  data() {
    return {
      props: {
        username: this.username,
        eventType: this.eventType,
        messagePlaceholderArea:
          this.eventType === "true"
            ? "Explica de qué trata tu evento..."
            : "¿Qué estás pensado, " + this.username + "?",
        messagePlaceholderTitulo:
          this.eventType === "true"
            ? "Nombre del evento"
            : "Título de la publicación",
      },
      multimedia: [],
      titulo: "",
      descripcion: "",
      eventprice: 0,
      showElement: false,
      dateevent: "",
      tipoevento: "1",
    };
  },
  mounted() {
    $$(function () {
      $$('[data-toggle="tooltip"]').tooltip();
    });

    //Restringiendo la fecha a que no sea una anterior al dia de hoy
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate =  year + '-' + month + '-' + day + "T00:00";
    this.$refs.date.min = maxDate;
    var x = this.$refs.date;
    console.log(x)


  },
  methods: {
    setListMedia(media) {
      this.multimedia = media;
    },
    publicarContent() {
      console.log("hey ,");
      if (this.titulo === "" || this.descripcion === "") {
        console.log("1");
        util("error", "La publicacion debe tener titulo y descripcion");
      } else if (this.eventprice < 1 && this.showElement == true) {
        console.log("3");
        util("error", "El precio del evento debe ser mayor a 0");
        return 0;

        console.log(this.dateevent);
      } else if (this.dateevent === "" && this.eventType === "true") {
        console.log("4");
        util("error", "Debe especificar la fecha del evento");
        return 0;
      } else {
        console.log("Hey");
        var data = {
          titulo: this.titulo,
          descripcion: this.descripcion,
          media: [...this.multimedia],
        };
        if (this.eventType == "true") {
          data = {
            ...data,
            precio: parseInt(this.eventprice),
            categoria: this.eventprice > 0 ? "pagado" : "gratis",
            tipoevento: this.tipoevento,
            fechaevento: this.dateevent,
          };
        }
        console.table(data);
      }
    },
    openPriceModal(event) {
      this.eventprice = 0;
      if (event.target.value == "2") this.showElement = true;
      if (event.target.value == "1") this.showElement = false;
    },
    addPrice() {},
  },
};
</script>
