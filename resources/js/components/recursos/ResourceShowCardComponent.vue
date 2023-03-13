<template>
    <div class="col-12 col-md-6">
        <div class="bg1rc">
            <div class="row">
                <div class="col">
                    <div class="p-0 acm-img m-1 border1rc" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]"></div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="d-flex flex-column h-100 justify-content-center">
                        <p>Tipo de recurso: {{itemData.resource.tipo_valor}}</p>
                        <h3>{{itemData.resource.name}}</h3>
                        <div>
                            <a href="javascript:void;" @click.prevent="goRead"> Seguir leyendo</a>
                        </div>
                    </div>
                </div>
            </div>    
        </div>    
        <div>
        </div>
    </div>
</template>

<script>


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

    },
    computed: {
        srcPresentationImg: function(){
            if(this.itemData.presentation_model == null){
                return this.acAppData.base_url + "/images/default_book.jpg";
            }else{
                return this.itemData.presentation_model.url;
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

    .mlist_item-description{
        width: 100%;
        height: 100px;
        position: relative;
        overflow: hidden;
    }

    .mlist_item-gradient{
        position: absolute;
        top: 0;
        background-image: -prefix-linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.4));
        background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.4));
        width: 100%;
        height: 100%;
    }

    .bg1rc{
        background-color: #e0e2e4;
    }

    .border1rc{
        border: 4px solid #363333
    }
</style>