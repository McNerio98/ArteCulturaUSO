<template>
    <div class="_wpBuildingPage pt-md-4 pt-1 pb-md-4 pb-1">
        <img :src="obj_ac_app.base_url + '/images/builders.svg'" alt="">
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
        props: {
            page: {type: String, required: true},
            obj_ac_app: undefined,

        },
        created: function(){
            this.obj_ac_app = window.obj_ac_app
        },
        mounted() {
            if(! this.obj_ac_app){alert("Error grave, objeto global no localizado");}
        }

    }
</script>
