<template>
  <div
    class="modal fade"
    id="trimModalComponent"
    data-backdrop="static"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalScrollableTitle"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalScrollableTitle">
            RECORTAR IMAGEN
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input
            class="d-none"
            type="file"
            id="hiddenImgFileTrim"
            @change="showPanelTrim"
          />
          <div class="row">
            <div class="col-12">
              <img src="" alt="" id="cropperNaturalPhoto" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
          </button>
          <button type="button" class="btn btn-primary" @click="toConvertBase64">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      cropper_el: null,
      image_tag: null,
      croppable: false,
      modal: null,
      cropBoxData: null,
      canvasData: null,
      mensaje_test: "Estoy en el contenido, scope",
    };
  },
  methods: {
    showPanelTrim: function (e) {
      //Validacion tamaños y otros de la imagen
      $("#trimModalComponent").modal("show");
      let urlImage = URL.createObjectURL(e.target.files[0]);
      this.image_tag.setAttribute("src", urlImage);
    },
    toConvertBase64: function(){
        let base64 = null;
        let canvas = this.cropper_el.getCroppedCanvas();
        base64 = canvas.toDataURL("image/jpeg");
        this.$emit("base64-generated",base64);
        $(this.modal).modal('hide');
    }
  },
  mounted() {
    this.image_tag = document.querySelector("#cropperNaturalPhoto");
    this.modal = $("#trimModalComponent")[0];
    let vm = this;
    $(this.modal)
      .on("shown.bs.modal", function () {
        vm.cropper_el = new Cropper(vm.image_tag, {
          aspectRatio: 1 / 1,
          viewMode: 1,
          ready: function () {
            vm.croppable = true;
            vm.cropper_el
              .setCropBoxData(vm.cropBoxData)
              .setCanvasData(vm.canvasData);
          },
        });
      })
      .on("hidden.bs.modal", function () {
        vm.cropBoxData = vm.cropper_el.getCropBoxData();
        vm.canvasData = vm.cropper_el.getCanvasData();
        vm.cropper_el.destroy();
      });
    console.log("Componente de recorte montado");
  },
};
</script>

<style scoped>
    img {
    display: block;
    /* This rule is very important, please don't ignore this */
    max-width: 100%;
    }
</style>
