<template>
    <li class="item">
        <div class="product-img"><img :src="user.path_media" alt="User Image" class="img-size-50"></div>
        <div class="product-info">
            <a :href="pathRedirect" class="product-title">{{user.artistic_name == undefined || (user.artistic_name.trim().length == 0) ? '(No Especificado)' : user.artistic_name }}
                <span class="badge badge-warning float-right">Perfil</span>
            </a>
            <span class="product-description">
                {{user.rubros}}
            </span>
        </div>
    </li>
</template>

<script>
    export default {
        props: {
            user: {type: Object,required:true},
            pathRedirect: {type: String,required: true},
        },
        data(){
            return {

            }
        },
        mounted() {
            console.log('Ciclo de vida del componente.')
        }
    }
</script>
