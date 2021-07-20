<template>
    <div class="card mb-3 alterCard">
        <div class="card-header bg-secondary alterHeader">
            <span>{{ title }}</span>
        </div>
        <div class="card-body">

            <!-- <div class="frontPanelSending">
              <div>
                  contenido
              </div>
            </div> -->

            <post-form-component :post-type="postType" @post-chiild-created="emitCreatedId"
                :user-data="userInfo">
            </post-form-component>
        </div>
    </div>
</template>

<script>
export default {
  props: {
      userInfo:{
        type: Object,
        default: function(){
          return {username:"(No Especificado)", profile_path: "https://i.insider.com/51c1d74aecad048224000021?width=762&format=jpeg"}
        }
      },
      postType: {type: String,default:"post"}
  },
  data: function () {
    return {
        title: this.postType == "event" ? "Crear Evento" : "Publicar contenido"
    };
  },
  methods: {
    emitCreatedId: function(post ){
//      console.log("Se creo un post con este id " + id_created);
      this.$emit("post-created",post);
    }
  }
};
</script>

<style scoped>
  .alterHeader{
    border-top-left-radius: 0px !important;
    border-top-right-radius: 0px !important;
  }

  .alterCard{
    max-width: 600px;
    width: 100%;
    margin: auto;    
  }

.loader,
.loader:before,
.loader:after {
  background: #ffffff;
  -webkit-animation: load1 1s infinite ease-in-out;
  animation: load1 1s infinite ease-in-out;
  width: 1em;
  height: 4em;
}
.loader {
  color: #ffffff;
  text-indent: -9999em;
  margin: 88px auto;
  position: relative;
  font-size: 11px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}
.loader:before,
.loader:after {
  position: absolute;
  top: 0;
  content: '';
}
.loader:before {
  left: -1.5em;
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}
.loader:after {
  left: 1.5em;
}
@-webkit-keyframes load1 {
  0%,
  80%,
  100% {
    box-shadow: 0 0;
    height: 4em;
  }
  40% {
    box-shadow: 0 -2em;
    height: 5em;
  }
}
@keyframes load1 {
  0%,
  80%,
  100% {
    box-shadow: 0 0;
    height: 4em;
  }
  40% {
    box-shadow: 0 -2em;
    height: 5em;
  }
}

.frontPanelSending{
    background-color: white;
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0px;
    left: 0px;
    z-index: 90;
    opacity: 0.5;
    display: flex;
    justify-content: center;
    align-items: center;  
}

</style>