<template>
    <div class="card mb-3 alterCard">
        <div class="card-header bg-secondary alterHeader">
            <span>{{ title }}</span>
        </div>
        <div class="card-body">
            <post-form-component :post-type="postType" @post-id-created="emitCreatedId"
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
</style>