<template>
  <div class="_acTrimPanel" id="trimComponentAC">
    <div class="_acTrimWrappen">
        <div class="_acTrimContent">
            <div>
                <img  src="" alt=""  id="elementPictureNaturalAC" />
            </div>
            <div class="_acTrimOptions">
              <button class="btnTrimOptions btnOk" @click="toConvertBase64">Guardar</button>
              <button class="btnTrimOptions btnCancel" @click="closeTrim">Cancelar</button>
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
    minCropBoxWidth: {type: Number,default: 100},
    minCropBoxHeight: {type: Number, default: 100}
  },
  data: function () {
    return {
      //cropper intsnace 
      cropper_el: null,
      //@ {HML Img Element}
      image_tag: null,
      //If the element was fully loaded and ready to trim 
      croppable: false,
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
      var valid_format = ["jpg","jpeg","png","JPG","JPEG","PNG"];
      var exten1 = file_target.name.split(".");
      if(valid_format.indexOf(exten1[exten1.length -1]) == -1)valid_file = false;
      if(file_target == undefined)valid_file = false;
      if(file_target.name == undefined)valid_file = false;
      if(!valid_file){alert("Error de formato, recargue el sitio"); this.closeTrim(); return;}

      let urlImage = URL.createObjectURL(file_target);
      this.image_tag.setAttribute("src", urlImage);
      let vm = this;

      $(vm.modal).fadeIn();     
      $("body").addClass("no-scroll");

      this.cropper_el = new Cropper(vm.image_tag, {
          aspectRatio             : vm.aspectRatio,
          viewMode                : vm.viewMode,
          minCropBoxWidth   : vm.minCropBoxWidth,
          minCropBoxHeight  : vm. minCropBoxHeight,

          ready: function () {
            //vm.croppable = true;                  
            vm.cropper_el
              .setCropBoxData(null)
              .setCanvasData(null);            
          },
      });      
    },
    closeTrim: function(){
        this.croppable = false;
        this.cropper_el.destroy();
        $("body").removeClass("no-scroll");
        $(this.modal).fadeOut();
        this.$emit("oncancel");
    },
    onClickClose: function(){
      //this.toConvertBase64();
      this.closeTrim();
    },
    toConvertBase64: function(){
        let base64 = null;
        let canvas = this.cropper_el.getCroppedCanvas();
        base64 = canvas.toDataURL("image/jpeg");
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

