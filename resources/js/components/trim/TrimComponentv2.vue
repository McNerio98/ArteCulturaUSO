<template>
  <div class="_acTrimPanel" id="trimComponentAC">
    <div class="_acTrimWrappen">
        <div class="_acTrimContent">
            <div>
                <img  src="" alt=""  id="elementPictureNaturalAC" />
            </div>
            <div class="_acTrimOptions">
              <button class="btnTrimOptions btnOk" @click="toConvertBase64">Guardar</button>
              <button class="btnTrimOptions btnCancel" @click="onClickClose">Cancelar</button>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    aspectRatio: {type: Number,default: 1},
    viewMode: {type: Number,default: 1},
    minCropBoxWidth: {type: Number,default: 140},
    minCropBoxHeight: {type: Number, default: 140}
  },
  data: function () {
    return {
      //cropper instance 
      cropper_el: null,
      //@ {HML Img Element}
      image_tag: null,
      //HTML modal element 
      modal: null,

      cropBoxData: null,
      canvasData: null,      
    };
  },
  mounted: function(){
    //getting HTML Elements reference
    this.modal          = document.getElementById("trimComponentAC");
    this.image_tag  = document.getElementById("elementPictureNaturalAC");
  },
  methods: {
    openTrim: function(file_target){
      //validate file Element
      let valid_file = true;
      const validExten = ["jpeg","jpg","png"];
      const  extenstion = file_target.name.substring(file_target.name.lastIndexOf('.')+1, file_target.name.length) || null;
      if(extenstion == null || !validExten.includes(extenstion.toLowerCase().trim())){
        this.closeTrim();
        StatusHandler.ValidationMsg("Archivos no soportados");
        return;
      }
      
      //validar tamaños
      const urlImage = URL.createObjectURL(file_target);
      const img = new Image();
      const vm = this;
      img.onload = function(){
        if(this.width < 150 || this.height < 150){
          vm.closeTrim();
          StatusHandler.ValidationMsg("Imagen pequeña, seleccione una más grande");
        }else{
          vm.setupForOpen(urlImage);
        }
      }
      img.src = urlImage;
    },
    setupForOpen: function(url){
      //aqui, eliminar posible instancia anterior 
      this.destroy();

      this.image_tag.setAttribute("src", url);
      const vm = this;
      $(vm.modal).fadeIn();     
      $("body").addClass("no-scroll");

      this.cropper_el = new Cropper(vm.image_tag, {
          aspectRatio             : vm.aspectRatio,
          viewMode                : vm.viewMode,
          minCropBoxWidth   : vm.minCropBoxWidth,
          minCropBoxHeight  : vm. minCropBoxHeight,

          ready: function () {
            vm.cropper_el.setCropBoxData(null).setCanvasData(null);            
          },
      });

    },
    closeTrim: function(){
        $("body").removeClass("no-scroll");
        $(this.modal).fadeOut();
        this.$emit("oncancel");
    },
    destroy: function(){
      if(this.cropper_el != null){
        this.cropper_el.destroy();
      }
    },
    onClickClose: function(){
      //this.toConvertBase64();
      this.closeTrim();
      this.destroy();
    },
    toConvertBase64: function(){
        let base64 = null;
        let canvas = this.cropper_el.getCroppedCanvas();
        base64 = canvas.toDataURL("image/jpeg");//aqui esta dando error 
        this.$emit("base64-generated",base64);
        this.closeTrim();
    }
  }
};
</script>
<style scoped>
    ._acTrimPanel{
        position: fixed;
        background-color: #31333fef;
        width: 100%;
        height: 100vh;
        z-index: 2000;
        overflow-y: scroll;
        padding: 10px;
        top: 0px;          
        left: 0px;
        display: none;
    }

    ._acTrimWrappen{
      width: 100%;
      height: 100%;
      padding: 5px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    ._acTrimContent{
      width: 100%;
      max-width: 600px;
    }

    ._acTrimOptions{
      padding: 10px 5px;
      display: flex;
      justify-content: center;
    }

    .btnTrimOptions{
      border: 0px;
      padding: 5px 15px;
      display: inline-block;
      color: white;
      border-radius: 10px;
      margin: 5px 10px;
    }

    .btnOk{
      background-color: #00b05c;
    }

    .btnCancel{
      background-color: #6a6e77;
    }

    img {
      max-width: 100%;
    }
</style>

