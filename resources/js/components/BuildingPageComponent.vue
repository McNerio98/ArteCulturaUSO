<template>
    <div class="_wpBuildingPage pt-md-4 pt-1 pb-md-4 pb-1">
        <img :src="acAppData.base_url + '/images/builders.svg'" alt="">
        <h5 class="text-muted">{{page}}</h5>
        <h3 class="_acGreen">¡¡Espérala pronto!!</h3>
        <p>Esta página se encuentra en proceso de creación</p>
    </div>
</template>
<style scoped>
    ._wpBuildingPage{
    width: 100%;
    max-width: 700px;
    margin: auto;
    text-align: center;  
    }

    ._wpBuildingPage img{
        width: 50%;
        max-width: 250px;
    }

</style>
<script>
    export default {
        data: function(){
            return {
                acAppData: {}
            }
        },
        props: {
            page: {type: String, required: true},
        },
        created: function(){
            if(!window.obj_ac_app){
                alert("Error fatal");
                return;
            }
            this.acAppData = window.obj_ac_app;
        },
        mounted() {
            if(! this.acAppData){alert("Error grave, objeto global no localizado");}
        }

    }
</script>
