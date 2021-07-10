<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12 titleContainer mb-2">
          <img
            width="60px"
            height="60px"
            style="object-fit: cover"
            class="img-circle img-bordered-sm"
            :src="userData.profile_path"
            alt="user image"
          />
          <div style="width: 100%; margin-left: 10px" class="form-group mb-0">
            <input
              v-model="post_title"
              v-bind:placeholder="place_holder_title"
              width="100%"
              type="email"
              style="margin-bottom: 0px;"
              class="form-control"
              id="exampleInputEmail1"
              aria-describedby="emailHelp"
            />
          </div>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-12 col-lg-4 col-md-4">
              <div class="form-group mb-0" v-if="postType == 'event'">
                <label for="exampleFormControlTextarea1" class="text-muted">Fecha/hora</label>

                 <date-picker v-model="time1" 
                 type="datetime" 
                 confirm  
                 format="DD/MM/YYYY hh:mm" 
                 v-on:change="verfecha"
                 :clearable="false"
                 :editable="false"></date-picker>
              </div>
            </div>
            <div class="col-12 col-lg-4 col-md-4">
              <div class="form-group mb-0" v-if="postType == 'event'">
                <label for="exampleFormControlTextarea1" class="text-muted">Tipo de evento</label >
                <select v-model="event_type" class="custom-select">
                  <option selected value="1">Eventual</option>
                  <option value="2">Permanente</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4 col-md-4">
              <div class="form-group mb-0" v-if="postType == 'event'">
                <label for="exampleFormControlTextarea1" class="text-muted">Categoria</label>
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
            <div v-if="show_panel_price" class="col-12 col-lg-12 col-md-12">
              <div class="form-group mb-0">
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
                    v-model="event_price"
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
          <div class="form-group mb-0">
            <label for="exampleFormControlTextarea1"></label>
            <textarea
              v-model="description"
              style="resize: none"
              class="form-control"
              v-bind:placeholder="place_holder_msg"
              id="exampleFormControlTextarea1"
              rows="3"
            ></textarea>
          </div>
        </div>
        <div class="col-12">

          <post-media-component @media="setListMedia"></post-media-component>

        </div>
        <div class="col-12">
          <br />
          <label
            style="width: 100%; cursor: pointer"
            type="button"
            @click="publicarContent"
            class="btn btn-success btn-block"
          >
            Publicar
          </label>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import DatePicker from 'vue2-datepicker';
  import 'vue2-datepicker/index.css';
  import 'vue2-datepicker/locale/es';
  const {createPostEvent } = require("../../api/api.service");

export default {
  components: { DatePicker },
  props: { //Las props deben ir en formato camell case 
      userData: {type: Object,required:true}, //name,img-profile
      postType: {type: String,default: "post"} 
  },
  data: function(){
    return {
      place_holder_msg: this.postType === "event"?"Explica de qué trata tu evento...":"¿Qué estás pensado "+this.userData.username+"?",
      place_holder_title: this.postType === "event"?"Nombre del evento":"Título de la publicación",
      post_title: "",
      event_date: "",
      event_type: "1",
      show_panel_price: false,
      event_price: 0.0,
      description: "",
      time1: new Date(),
      multimedia: []
    }
  },
  mounted(){
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });    
  },
  methods: {
    cleanForm: function(){
      this.post_title = "";
      this.description = "";
      this.multimedia = [];
      this.event_price = 0.0;
    },
    setListMedia: function (media) {
      this.multimedia = media;
    },    
    openPriceModal: function(event){
      this.event_price = 0.00;
      this.show_panel_price =  event.target.value == "2"?true:false;
    },
    publicarContent: function(){
      if(this.post_title.trim().length < 2 || this.description.trim().length < 2){
        StatusHandler.ValidationMsg("El título y descripción son requeridos");
        return;
      }

      if(this.show_panel_price == true && this.event_price < 1){
          StatusHandler.ValidationMsg("El costo de asistencia del evento debe ser mayor que 0");
          return;
      }

      if(this.postType == "event" && this.time1 == null){
          StatusHandler.ValidationMsg("Debe especificar una fecha para el evento");
          return;        
      }
      
      let data_send = {
          post_type: this.postType,
          title: this.post_title,
          description: this.description,
          media: [...this.multimedia]        
      };

      if(this.postType == "event"){
        data_send = {
          ...data_send,
          event_price: parseFloat(this.event_price).toFixed(2),
          event_category: this.event_price > 0?"pagado":"gratis",
          event_type: this.event_type == "1"?"eventual":"permanente",
          event_date: this.time1.toISOString()
        }
      }

      console.table(data_send);

      axios.post(`/api/post`,data_send).then((result) => {
          let response = result.data;
          if(response.code == 0){
            StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
            return;
          }        
            console.log(response);
            this.cleanForm(); 
            console.log("Antes de emitir ......");
            this.$emit('post-id-created',response.data);
            
      }).catch((ex) => {
            console.log(ex);
      })

    },
    verfecha: function(mx){
      console.log("Estoy entrando desde la fecha con estos valores");
      console.log(mx);
      console.log(this.time1);
    }
  }
}
</script>

<style scoped>
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
.titleContainer{
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mx-input{
	height: calc(2.25rem + 2px) !important;
	padding: 0.375rem 0.75rem !important;
	font-size: 1rem !important;
}

.mx-datepicker {
	display: block !important;
	width: 100% !important;
}


</style>
