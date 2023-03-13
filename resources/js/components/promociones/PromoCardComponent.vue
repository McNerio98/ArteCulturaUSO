<template>
    <div class="col-sm-12 col-md-6 mb-3">
        <div :style="[{ backgroundImage: 'url(' +  itemData.promo.image.url + ')' }]" class="ac_promo-bg-image-card">
            <div class="position-relative p-3 ac_promo-bg-panel" style="height: 300px">
                <div class="ribbon-wrapper ribbon-xl">
                    <div class="ribbon" :class="[classConfig]">
                        {{getTipoLabel(itemData.promo.type_ads)}}
                    </div>
                </div>
                <p class="text-white m-0">{{itemData.promo.title}}</p>
                <p>
                    <a href="javascript:void(0);" @click="goRead">Ver mas</a> 
                    <span class="text-success">|</span> 
                    <a href="javascript:void(0);" @click="goPreview">Previsualizar</a>
                </p>                
                <small class="text-white">{{itemData.promo.description}}</small>
            </div>
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
            acAppData: window.obj_ac_app,
            classConfig: "bg-secondary",
            itemData: JSON.parse(JSON.stringify(this.pdata))            
        }
    },
    methods: {
        getTipoLabel: function(type_ads){
            var label = "";
            const idTipo = isNaN(parseInt(type_ads)) ? 0 : parseInt(type_ads);

            switch(idTipo){
                case 1: {label = "Post/Evento";this.classConfig="bg-success" ;break;}
                case 2: {label = "Homenajes";this.classConfig="bg-secondary";break;}
                case 3: {label = "Recurso";this.classConfig="bg-warning";break;}
                case 4: {label = "Perfil";this.classConfig="bg-info";break;}                
                default : {label = "(No Definido)";break;}                                                
            }

            return label;
        },
        goRead: function(){
            this.$emit('on-read',this.itemData.promo.id);
        },
        goPreview: function(){
            this.$emit('on-preview',this.itemData.promo.item_id,this.itemData.promo.type_ads);
        }
    }    
}
</script>

<style scoped>
.ac_promo-bg-image-card{
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;    
}
.ac_promo-bg-panel{
    background-image: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2));
}
</style>