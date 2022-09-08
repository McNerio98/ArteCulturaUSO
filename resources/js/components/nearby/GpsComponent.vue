<template>
    <div class="_wpBuildingPage pt-md-4 pt-1 pb-md-4 pb-1">
        <img src="/images/icons/gps-svgrepo-com.svg" />
        <p class="text-muted">Para mostrarte los eventos cercanos necesitamos acceder a tu ubicación</p>
        <button type="button" @click="onActiveGps" class="btn btn-success">Activar Ubicacion</button>
        <button type="button" @click="onBack" class="btn btn-dark">Regresar</button>
    </div>
</template>

<script>
export default {
    props: {
        urlBack: {
            required: true,
            type: String
        }
    },
    methods: {
        onActiveGps: function(){
            const sucess = (position) =>{
                this.$emit('on-active',position);
            }

            const error = (ex) => {
                this.$emit('on-denied',ex);
            }
            navigator.geolocation.getCurrentPosition(sucess,error);
        },
        onBack: function(){
            window.location.href = this.urlBack;
        }
    }
    
}
</script>

<style scoped>
    ._wpBuildingPage{
    width: 100%;
    max-width: 700px;
    margin: auto;
    text-align: center;  
    }

    ._wpBuildingPage img{
        width: 50%;
        max-width: 175px;
    }


</style>