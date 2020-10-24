<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12 titleContainer">
          <form v-on:submit.prevent="onSubmit">
            <div class="form-row">
              <div class="form-group col-12">
                <label for="exampleInputEmail1">NOMBRE COMPLETO</label>
                <input
                  v-model="fullname"
                  type="text"
                  required
                  maxlength="70"
                  class="form-control"
                  id="exampleInputEmail1"
                  aria-describedby="emailHelp"
                />
              </div>
              <div class="form-group col-6">
                <label for="exampleInputEmail1">CORREO ELECTRONICO</label>
                <input
                  v-model="correo"
                  type="email"
                  required
                  maxlength="50"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Ej. example@email.com"
                  aria-describedby="emailHelp"
                />
              </div>
              <div class="form-group col-6">
                <label for="exampleInputEmail1">NUMERO DE TELEFONO</label>
                <input
                  v-model="tel"
                  type="tel"
                  required
                  maxlength="10"
                  class="form-control"
                  placeholder="Ej. 7777-7777"
                  id="exampleInputEmail1"
                  aria-describedby="emailHelp"
                />
              </div>
              <div class="form-group col-6">
                <label for="exampleInputEmail1">NOMBRE ARTISTICO</label>
                <input
                  required
                  v-model="artistic"
                  type="tel"
                  maxlength="50"
                  class="form-control"
                  id="exampleInputEmail1"
                  aria-describedby="emailHelp"
                />
                <small id="emailHelp" class="form-text text-muted"
                  >*Escribe el nombre que te representa como
                  artista/banda/entidad.</small
                >
              </div>
              <div class="form-group col-6">
                <label for="exampleInputEmail1">SELECCIONA EL RUBRO</label>
                <select required v-model="rubro" class="custom-select">
                  <option value="0" disabled selected>Elegir</option>
                  <option
                    v-for="(item, key) in listTags"
                    v-bind:key="key"
                    :value="item.id"
                  >
                    {{ item.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Cancelar
              </button>
              <input
                type="submit"
                class="btn btn-primary"
                value="Enviar Solicitud"
              />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as $$ from "jquery";
import * as bootstrap from "bootstrap";
const { util, getTags, requestAccount } = require("../api/api.service");

export default {
  data() {
    return {
      listTags: [],
      rubro: 0,
      fullname: "",
      correo: "",
      tel: "",
      artistic: "",
    };
  },
  beforeMount() {
    getTags()
      .then((tags) => {
        this.listTags = tags.data;
        console.log(this.listTags);
      })
      .catch((e) => {
        alert("No se puede obtener los rubros: " + e);
      });
  },
  methods: {
    onSubmit() {
      console.log("No se cargue");
      if (this.rubro === 0) {
        util("error", "Debe especificar el rubro que pertenece");
      } else {
        this.SolicitarCuenta();
      }
    },
    SolicitarCuenta() {
      const data = {
        name: this.fullname,
        email: this.correo,
        telephone: this.tel,
        rubros: this.rubro,
        artistic_name: this.artistic,
      };

      console.log("DATA TO SEND", data);

      requestAccount(data)
        .then((result) => {
          console.log(result);
          if (result) {
            if (result.data.codeStatus == 1) {
              util("success", result.data.msg);
              this.rubro = 0;
              this.fullname = "";
              this.correo = "";
              this.tel = "";
              this.artistic = "";
              $$("#exampleModal").modal("hide");
            }
          }
        })
        .catch((e) => {
          console.log(e);
          util("error", e);
        });
    },
  },
};
</script>
