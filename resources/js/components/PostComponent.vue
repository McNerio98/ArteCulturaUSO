<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mb-3">
          <div class="card-header bg-secondary">
            <span>{{ title }}</span>
          </div>
          <div class="card-body">
            <postFormulario-component
              username="Ana Vel"
              v-bind:eventType="props.type"
            ></postFormulario-component>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["type"],
  data: function () {
    return {
      props: {
        type: this.type,
      },
      title: this.type == "true" ? "Crear Evento" : "Publicar contenido",
    };
  },
};
</script>
