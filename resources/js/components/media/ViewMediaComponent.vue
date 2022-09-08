<template>
    <div class="modal fade" id="modaPreviewMedia" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="ModalMedia"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding-top: 5px !important;padding-bottom: 5px !important;">
                    <button type="button" class="close" @click="onClose" aria-label="Close">
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
                <div  class="modal-footer" style="justify-content: center !important;">
                    <template v-if="openFor == 'PROFILE_MEDIAS' && Selected.owner.id === acAppData.current_user.id">
                        <button @click="$emit('new-profileimg')" type="button" class="btn btnOptionsPreMedia">Subir nueva</button>
                    </template>
                    <template v-if="openFor == 'PROFILE_MEDIAS' && Selected.owner.id === acAppData.current_user.id && acAppData.current_user.presentation_img.id != Selected.id ">
                        <button @click="$emit('setlike-perfil',Selected.id)"  type="button" class="btn btnOptionsPreMedia">Seleccionar como Perfil </button>
                    </template>       
                    <template v-if="openFor == 'PROFILE_MEDIAS' && Selected.owner.id === acAppData.current_user.id && Selected.name != 'default_img_profile.png'">
                        <button @click="$emit('delete',Selected.id)"  type="button" class="btn btn-warning">Eliminar</button>
                    </template>                            
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                target: null,
                openFor: "",
                items: [],
                acAppData: window.obj_ac_app,        

                current_media: "",
                index_current: -1,
                aux_mutated: false,
                dimg: {width: "0%", height: "0%"}
            }
        },
        methods: {
            builderAndShow: function(items,open_for,target){
                this.items = items;
                this.openFor = open_for;
                this.target = target;
                $('#modaPreviewMedia').modal('show');
            },
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
           },
           onClose: function(){
            this.items = [];
            this.openFor = "";
            this.target = null;
             $('#modaPreviewMedia').modal('hide');
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