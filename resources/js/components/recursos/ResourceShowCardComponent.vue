<template>
    <div class="col-12 col-md-6">
        <div class="bg1rc">
            <div class="row">
                <div class="col">
                    <div class="p-0 acm-img m-1 border1rc" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]"></div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="d-flex flex-column h-100 justify-content-center">
                        <p>Tipo de recurso: {{getTipo}}</p>
                        <h3>{{itemData.resource.name}}</h3>
                    </div>
                </div>
            </div>    
        </div>    
        <div>
            <p>
                {{itemData.resource.description}}
                <a href="javascript:void;" @click.prevent="goRead"> Seguir leyendo</a>
            </p>
        </div>
    </div>
</template>

<script>
import {getTiposRecursos} from '../../service';

export default {
    props: {
         pdata: {type: Object,required:true}
    },
    data: function(){
        return {
            tiposRecursos: [],
            acAppData: window.obj_ac_app,
            itemData: JSON.parse(JSON.stringify(this.pdata))
        }
    },
    created: function(){
        getTiposRecursos().then(result =>{
            this.tiposRecursos = result.data;
        });
    },
    computed: {
        srcPresentationImg: function(){
            if(this.itemData.presentation_model == null){
                return this.acAppData.base_url + "/images/default_book.jpg";
            }else{
                return this.itemData.presentation_model.url;
            }
        },
        getTipo: function(){
            if(this.tiposRecursos.length > 0){
                return this.tiposRecursos[this.itemData.resource.tipo_id - 1].option;
            }else{
                return "No Especificado";
            }
        }   
    },
    methods: {
        goRead: function(){
            this.$emit("on-read",this.itemData.resource.id);
        }
    }
    
}
</script>

<style scoped>
    .acm-img{
        background-color: gray;
        width: 100%;
        aspect-ratio: 2/3;
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Do not repeat the image */
        background-size: cover; /* Resize the background image to cover the entire container */        
    }

    .bg1rc{
        background-color: #d2d6de;
    }

    .border1rc{
        border: 4px solid #363333
    }
</style>