<template>
    <li class="item">
        <div class="product-img"><img :src=" '../files/images/'+user.img_profile" alt="Product Image" class="img-size-50"></div>
        <div class="product-info">
            <a :href="pathRedirect" class="product-title">{{user.artistic_name}}
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
        props: ["user",'pathRedirect'],
        data(){
            return {

            }
        },
        mounted() {
            console.log('Ciclo de vida del componente.')
        }
    }
</script>
