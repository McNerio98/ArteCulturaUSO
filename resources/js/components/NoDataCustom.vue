<template>
    <div class="_wpBuildingPage pt-md-4 pt-1 pb-md-4 pb-1">
        <img :src="acAppData.base_url + '/images/icons/'+icon" alt="">
        <h5 class="text-muted">{{header}}</h5>
        <h3 class="_acGreen">{{title}}</h3>
        <p>{{subtitle}}</p>
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
            icon: {
                type: String,
                default: "box.svg"
            },
            header: {
                type: String, 
                default: "Aún no se han registrado elementos"
            },
            title: {
                type: String, 
                default: "¡¡Espérala pronto!!"
            },
            subtitle: {
                type: String,
                default: "Esta página aún no se registran items"
            }
        }
    }
</script>
