<template>
    <div class="col-md-6">
        <div class="row border rounded flex-md-row mb-4 shadow-sm">
            <div class="col-8">
                <div class="d-flex flex-column p-1 p-md-4 acm-content">
                    <strong class="d-inline-block mb-2 text-primary">{{itemData.memory.area}}</strong>
                    <h3 class="mb-0">{{itemData.memory.name}}</h3>
                    <div class="mb-1 text-muted">{{itemData.memory.birth_date | DateFormatES2}}</div>
                    <p style="word-break: break-all;" class="card-text mb-auto" v-html="contentShort"></p>
                    <a href="javascript:void;" @click.prevent="onGoRead" class="stretched-link">Seguir leyendo</a>
                </div>
            </div>
             <div class="col acm-img p-0" :style="[{ backgroundImage: 'url(' +  srcPresentationImg + ')' }]">
                <div v-if="this.itemData.memory.presentation_model != null" class="acm-imgwrpp"></div>
             </div>
        </div>
    </div>
</template>
<style scoped>
    .acm-imgwrpp{
        background: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(0,212,255,0) 46%);
        width: 100%;
        height: 100%;
    }

    .acm-img{
        background-color: gray;
        width: 100%;
        aspect-ratio: 2/3;
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Do not repeat the image */
        background-size: cover; /* Resize the background image to cover the entire container */        
    }

    .acm-content{
        justify-content: space-between;
    }

    .acm-dots{
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        width:100%; 
    }
</style>
<script>
    export default {
        props: {
            pdata: {type: Object,required:true}
        },
        data: function(){
            return {
                itemData: JSON.parse(JSON.stringify(this.pdata)),
                acAppData: window.obj_ac_app,
                flags: {}
            }
        },
        computed: {
            contentShort: function(){
                var text = this.itemData.memory.content || "";
                return text.substring(0,50) +"...";
            },
            srcPresentationImg: function(){
                if(this.itemData.presentation_model == null){
                    return this.acAppData.base_url + "/images/no_image_found.png";
                }else{
                    return this.itemData.presentation_model.url;
                }
            }
        },
        methods: {
            onGoRead: function(){
                this.$emit('on-read',this.itemData.memory.id);
            }
        }
    }
</script>
