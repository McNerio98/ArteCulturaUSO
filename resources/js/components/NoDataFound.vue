<template>
    <div class="_wpBuildingPage pt-md-4 pt-1 pb-md-4 pb-1">
        <img :src="acAppData.base_url + '/images/icons/'+icon" alt="">
        <h5 class="text-muted">No se encontraron {{product}}</h5>
        <h3 class="_acGreen">¡¡Espérala pronto!!</h3>
        <p>La búsqueda no encontró registros</p>
    </div>
</template>
<style scoped>
    ._wpBuildingPage{
        width: 100%;
        max-width: 500px;
        margin: auto;
        text-align: center;  
        padding: 10px;
    }

    ._wpBuildingPage img{
        width: 50%;
        max-width: 150px;
    }

</style>
<script>
    export default {
        data: function(){
            return {
                acAppData: window.obj_ac_app,
            }
        },
        props: {
            product: {type: String, default: "Registros"},
            icon: {type: String, default: "builders.svg"},
            labelDisplay: {type: Number,default: 1}
        }
    }
</script>
