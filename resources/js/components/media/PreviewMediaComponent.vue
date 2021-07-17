<template>
  <div class="pnlParent" v-if="items.length > 0" @click="$emit('source-files',items)">
    <!--DISEÑO UNO (1 MEDIO)-->
    <div class="pvm-wrappen" v-if="items.length == 1">
      <div class="_m1 _galHero" :style="{ backgroundImage: 'url(' + items[0].name + ')' }"></div>
    </div>
    <!--END DISEÑO UNO (1 MEDIO)-->

    <!--DISEÑO UNO (2 MEDIOS)-->
    <div class="pvm-wrappen" v-if="items.length == 2">
      <div class="_m2 _galHero"></div>
      <div class="_m2 _galHero"></div>
    </div>
    <!--END DISEÑO UNO (2 MEDIOS)-->

    <!--DISEÑO UNO ( 3  ||  3+  MEDIOs)-->
    <div class="pvm-wrappen" v-if="items.length >= 3">
      <div class="_m3 _galHero" :style="{ backgroundImage: 'url(' + items[0].name + ')' }"></div>
      <div class="_m3 d-flex flex-column">
          <div class="_scn _galHero" :style="{ backgroundImage: 'url(' + items[1].name + ')' }"></div>
          <div class="_scn _galHero" :style="{ backgroundImage: 'url(' + items[2].name + ')' }">
                <div class="hero-text _hero-text" v-if="items.length > 3">
                    <h1 style="font-size:50px">+ {{items.length - 3}}</h1>
                </div>          
          </div>
      </div>
    </div>
    <!--END DISEÑO UNO (3 || 3  MEDIOS)-->

  </div>
</template>

<script>
export default {
  props: {
    media: { type: Array, default: [] }
  },
  data: function(){
      return {
          //items: ""
      }
  },
  computed: {
      items: function(){
          //si es imagen video poner una imagen de "presentacion" 
          return  this.media.filter(e => e.type_file === "image" || e.type_file === "video");
      }
  },
  mounted() {
    console.log("Ciclo de vida del componente.");
  },
  methods: {

  }
  
};
</script>

<style scoped>
.pnlParent {
  position: relative;
  width: 100%;
  padding-top: 56.25%; /* 16:9 Aspect Ratio */
  margin-bottom: 10px;
  transition: opacity 0.5s;
}

.pnlParent:hover{
    cursor: pointer;
    opacity: 0.7;
}

.pvm-wrappen {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: #ffffff6c;
  display: flex;
}

._m1 {
  width: 100%;
  
}

._m2 , ._m3{
  width: 50%;
}

._m3 ._scn{
  width: 100%;
  height: 50%;
}

._galHero{
    background-color: #dad8d8;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;    
    border: 1px solid white;
}

._hero-text {
	width: 100%;
	height: 100%;
  	display: flex;
    padding: 5px;
    justify-content:center;
    align-items:center;
  	background-color: #ffffff80;
  	color: #18055c;
}
</style>