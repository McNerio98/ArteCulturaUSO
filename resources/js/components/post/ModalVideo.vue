<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <h4>Agregar video</h4>
          </div>

          <div class="modal-body">
            <img style="width: 100%;" :src="$parent.acAppData.base_url + '/images/step1_youtube.png' "  alt="step1">
            <p style="font-size: 18px" class="text-muted">
              Enlaza tu video de youtube copiando la URL.
            </p>
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
            <button
              type="button"
              class="btn btn-secondary"
              @click="$emit('close')"
            >
              Cancelar
            </button>
            <button
              @click="addVideo()"
              v-if="linkYoutube"
              type="button"
              class="btn btn-primary"
            >
              Aceptar
            </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  data: function () {
    return {
      linkYoutube: "",
    };
  },
  methods:{
      addVideo: function(){
          this.$emit("add", this.linkYoutube);
          this.$emit('close');
      }
  }
};
</script>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 300px;
  margin: 0px auto;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header{
   background: #fff;
}

.modal-header h4 {
  color: rgb(7, 132, 163);
}



.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
