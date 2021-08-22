<template>
    <div class="modal fade" id="modaPreviewMedia" tabindex="-1" role="dialog" aria-labelledby="ModalMedia"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding-top: 5px !important;padding-bottom: 5px !important;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="_acSelectedPreBox">
                        <div class="_acBtnNavigation" @click="preItem"><i class="fas fa-chevron-left"></i></div>
                        <div class="_acCurrentImgPreBox">

                            <iframe v-if="Selected.type == 'video'" style="width: 100%;height: 100%;" width="420" height="345" :src="'https://www.youtube.com/embed/' + Selected.name"></iframe>                                  
                            <img  v-if="Selected.type == 'image'" style="object-fit: contain; padding-top: 3px; width: 100%;height: 100%;" alt="" :src="Selected.url">
                            <!-- <div class="_acCurrentImgPnl">                              
                            </div> -->
                        </div>
                        <div class="_acBtnNavigation" @click="nextItem"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>
                <div v-if="typeMedia == 'PROFILE_MEDIAS' && target.owner.id === logged" class="modal-footer" style="justify-content: center !important;">
                    <button @click="$emit('new-profile-media')" type="button" class="btn btnOptionsPreMedia">Subir nueva</button>
                    <button @click="$emit('set-profile-media')"  type="button" class="btn btnOptionsPreMedia">Seleccionar como Perfil </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            logged: {type: Number,default: 0}, //id del usuario logeado 
            mediaProfile: {type: Boolean, default: false}, //deprecated, se sustituye por type-media
            typeMedia: {type: String}, // MEDIA_PROFILES (Images de perfiles)
            target: {type: Object,required:true}, //elemento actual objecto completo()
            loadedMedia: {type: Boolean,default: true},
            items: {type: Array, default: []}, //los items se deben pasar formateados 
            owner: {type:Number,default:0} //el id del usuario al que le pertenece el fichero actual 
        },
        data: function(){
            return {
                current_media: "",
                index_current: -1,
                aux_mutated: false,
                dimg: {width: "0%", height: "0%"}
            }
        },
        methods: {
           preItem: function(){
               //console.log("Anterior...");
               if( (this.index_current - 1) >= 0){
                    this.index_current--;
               }else{
                   this.index_current = this.items.length -1;
               }
           },
           nextItem: function(){
               //console.log("Next ...");
               if( (this.index_current + 1) >=this.items.length){
                   this.index_current = 0;
               }else{
                   this.index_current++;
               }
           }
        },
        watch: {
            target: function(a,b){
                for(let i = 0; i < this.items.length ; i++){
                    if(this.items[i].id == this.target.id){
                       this.index_current = i;
                       break;
                    }
                }      

            }
        },
        computed: {
            Selected: function(){
                if(this.items[this.index_current] == undefined){
                    return {}; //retornar de no encontrado 
                }

                // if(this.items[this.index_current].type == 'image'){
                //     const img = new Image();
                //     const vu = this;
                //     img.onload = function(){
                //         if((this.width - 150) > this.height){
                //             vu.dimg.width = "100%";
                //             vu.dimg.height = "auto";
                //         }else if(this.height > this.width){
                //             vu.dimg.width = "auto";
                //             vu.dimg.height = "100%";
                //         }else{ // cuadrada, dado el radio de aspecto se ajusta al alto 
                //             vu.dimg.width = "auto";
                //             vu.dimg.height = "100%";                            
                //         }
                        
                //     }
                //     img.src = this.items[this.index_current].url;
                // }

                //pasar mejor el fullpath 
                return this.items[this.index_current]; 
            }
        }
    }
</script>

<style scoped>
            ._acSelectedPreBox{
                width: 100%;
                margin: auto;
                display: flex;
                align-items: stretch;                
            }

            ._acSelectedPreBox ._acBtnNavigation{
                background-color: #f4f3f3;
                width: 5%;
                float: left;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #504e4e;
                font-size: 150%;
                padding: 5px;
                overflow: hidden; 
                cursor: pointer;
            }

            ._acSelectedPreBox ._acBtnNavigation:hover{
                background: #ecf4ff                               
            }

            ._acSelectedPreBox ._acCurrentImgPreBox{
                width: 90%;
                height: 80vh;
                background-color: #eae3e3;
                /*position: relative;*/
                /*padding-top: 100%;*/
                /*padding-top: 65%;*/  /* 4:3 Aspect Ratio */
                float: left;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;                
            } 

            ._acSelectedPreBox ._acCurrentImgPreBox ._acCurrentImgPnl{
                position: absolute;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;                
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;                
                justify-content: center;
            }

</style>