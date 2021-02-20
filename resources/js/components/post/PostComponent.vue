<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mb-3">
          <div class="card-header bg-secondary">
            <span>{{ title }}</span>
          </div>
          <div class="card-body">

            <post-form-component :post-type="postType" @post-id-created="emitCreatedId" :user-data="{username:'McNerio', profile_path: 'https://i.insider.com/51c1d74aecad048224000021?width=762&format=jpeg'}"></post-form-component>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
      postType: {type: String,default:"post"}
  },
  data: function () {
    return {
        title: this.postType == "event" ? "Crear Evento" : "Publicar contenido"
    };
  },
  methods: {
    emitCreatedId: function(id_created ){
      console.log("Se creo un post con este id " + id_created);
      this.$emit("post-created",id_created);
    }
  }
};
</script>