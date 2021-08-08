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
                <label for="exampleFormControlTextarea1" class="text-muted">Se repite</label >
                <select v-model="frequency" class="custom-select">
                  <option selected value="unique">No se repite</option>
                  <option value="repeat">Cada año</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-4 col-md-4">
              <div class="form-group mb-0" v-if="postType == 'event'">
                <label for="exampleFormControlTextarea1" class="text-muted">Categoria</label>
                <select
                  @change="openPriceModal" v-model="event_has_price" class="custom-select">
                  <option value="1">Pagado</option>
                  <option value="0">Gratuito</option>
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
              v-bind:placeholder="place_holder_msg + userData.nickname + '?'"
              id="exampleFormControlTextarea1"
              rows="3"
            ></textarea>
          </div>
        </div>
        <div class="col-12">

          <post-media-component :buffer="{edit_mode: editMode, medias: sourceEdit.media}" @media-del="setMediaDel" @media="setListMedia"></post-media-component>

        </div>      
        <div class="col-12">
          <br />
          <button 
            style="width: 100%; cursor: pointer"
            type="button"
            @click="publicarContent"
            class="btn btn-success btn-block">
            <span v-if="spinners.S1" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>            
            {{label_btn}}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import DatePicker from 'vue2-datepicker';
  import 'vue2-datepicker/index.css';
  import 'vue2-datepicker/locale/es';
  
export default {
  components: { DatePicker },
  props: { //Las props deben ir en formato camell case 
      userData: {type: Object,required:true}, //name,img-profile
      postType: {type: String,default: "post"},
      editMode: {type: Boolean, default: false},
      sourceEdit: {type: Object, default: function(){
        return {
          post: {
            id: 34,
            title: "Evento deportivo",
            description: "Detalle del evento",
            type: "post",
            is_popular: false,
            status: 'review',
            created_at: "2021/05/23",
          },
          dtl_event: {
            event_date: null,
            has_cost: false,
            cost: 45.67,
            frequency: "unique"
          },
          creator: {
            id: 3,
            name: "Mario Nerio",
            profile_img: "asdjusdaadd.png",
            nickame: "Cocolito"
          },
          media: [],
          metas: []          
        }
      }}
  },
  data: function(){
    return {
      label_btn: "Publicar", 
      spinners: {S1:false},
      post_title: "",
      event_date: "",
      frequency: "unique", 
      show_panel_price: false,
      event_has_price: "0",
      event_price: 0.0,
      description: "",
      time1: new Date(),
      multimedia: [],
      multimedia_del: [], //only for edit mode
    }
  },
  computed: {
    place_holder_msg: function(){
        return this.postType === "event"?"Explica de qué trata tu evento...":"¿Qué estás pensado ";
    },
    place_holder_title: function(){
        return this.postType === "event"?"Nombre del evento":"Título de la publicación";
    }
  },
  mounted(){

    $(function () {
        $('[data-toggle="tooltip"]').tooltip(); //??
    });    

  if(this.editMode){
      this.description = this.sourceEdit.post.description;
      this.post_title = this.sourceEdit.post.title;
      this.label_btn = "Guardar";
  }

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
    setMediaDel: function(del_list){
      this.multimedia_del = del_list;
    },
    openPriceModal: function(event){
      this.event_price = 0.00;
      this.show_panel_price =  this.event_has_price == "1"?true:false;
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
          StatusHandler.ValidationMsg("Debe especificar una fecha y hora para el evento");
          return;        
      }

      if(this.editMode){
            this.updatePostEvent ();
      }else{
          this.savePostEvent();
      } 
    },
    savePostEvent: function(){

      let data_send = {
          post_type: this.postType,
          title: this.post_title,
          description: this.description,
          media: [...this.multimedia]        
      };

      if(this.postType == "event"){
        data_send = {
          ...data_send,
          event_price: isNaN(parseFloat(this.event_price)) ? 0 : parseFloat(this.event_price).toFixed(2),
          event_has_price: this.event_has_price == "1"  ? true  : false,
          frequency: this.frequency,
          event_date: this.time1.toISOString()
        }
      }
      //Creando
      axios.post(`/postevent`,data_send).then((result) => {
          let response = result.data;
          if(response.code == 0){
            StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
            return;
          }        
          this.cleanForm(); 
          this.$emit('post-chiild-created',response.data);
            
      }).catch((ex) => {
            StatusHandler.Exception("Crear elemento",ex);
      }).finally(e=>{
            this.spinners.S1 = false;
      })

    },
    updatePostEvent: function(){
      console.log("editando el elemento");
      let data_send = {
          post_type: this.postType,
          title: this.post_title,
          description: this.description,
          media: []
      };
      data_send.media = this.multimedia.filter(e => e.id == null); //son los nuevos, (id == 0)
      data_send.mediadrop_ids = this.multimedia_del;//id de elementos a eliminar 


      if(this.postType == "event"){
        data_send = {
          ...data_send,
          event_price: isNaN(parseFloat(this.event_price)) ? 0 : parseFloat(this.event_price).toFixed(2),
          event_has_price: this.event_has_price == "1"  ? true  : false,
          frequency: this.frequency,
          event_date: this.time1.toISOString()
        }
      }
      //Actualizando 
      axios.put(`/postevent/${this.sourceEdit.post.id}`,data_send).then(result =>{
          let response = result.data;
          if(response.code == 0){
            StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
            return;
          }        
          this.cleanForm(); 
          this.$emit('post-chiild-created',response.data);
      }).catch(ex =>{
          StatusHandler.Exception("Actualizar elemento",ex);
      }).finally(e=>{
        this.spinners.S1 = false;
      });

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
