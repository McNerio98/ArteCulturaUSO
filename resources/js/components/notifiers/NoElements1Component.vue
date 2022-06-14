<template>
    <div class="container m-1 m-md3">
        <div class="row justify-content-center antrax">
            <img class="imgIcon1" :src="imgPath" alt="">
            <div class="p-1 p-md-2">
                <h5 class="actext-teal1">Ningún elemento registrado </h5>
                <p class="actext-gray-e1 tenue-card">Se el primero en aparecer en esta lista, <a :href="pathRegister"><u class="actext-teal1">crea una cuenta</u></a></p>                
            </div>
        </div>
    </div>
</template>
<style scoped>
    .imgIcon1{
        width: 50%;
        max-width: 100px;
    }
    p{
        font-size: 110%;
    }
    .antrax{
        align-items: center;        
    }

    .antrax img{
        width: 150px;
    }


</style>
<script>
    export default {
        props: {
            imgPath: {type: String, required: true},
            pathRegister: {type: String, require: true}
        },
    }
</script>
