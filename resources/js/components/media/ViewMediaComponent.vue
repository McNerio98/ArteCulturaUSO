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
                <div class="modal-body">
                    <div class="_acSelectedPreBox">
                        <div class="_acBtnNavigation" @click="preItem"><i class="fas fa-chevron-left"></i></div>
                        <div class="_acCurrentImgPreBox" v-bind:style="{ 'background-image': 'url(' + Selected + ')' }">
                        </div>
                        <div class="_acBtnNavigation" @click="nextItem"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>
                <div v-if="mediaProfile && owner === logged" class="modal-footer" style="justify-content: center !important;">
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
            logged: {type: Number,default: 0},
            paths: {type: Object, required: true},
            mediaProfile: {type: Boolean, default: false},
            targetId: {type: Number,required:true},
            loadedMedia: {type: Boolean,default: true},
            items: {type: Array, default: []},
            owner: {type:Number,default:0}
        },
        data: function(){
            return {
                current_media: "",
                index_current: -1,
                aux_mutated: false,
            }
        },
        methods: {
           preItem: function(){
               console.log("Anterior...");
               if( (this.index_current - 1) >= 0){
                    this.index_current--;
               }else{
                   this.index_current = this.items.length -1;
               }
           },
           nextItem: function(){
               console.log("Next ...");
               if( (this.index_current + 1) >=this.items.length){
                   this.index_current = 0;
               }else{
                   this.index_current++;
               }
           }
        },
        watch: {
            targetId: function(a,b){
                for(let i = 0; i < this.items.length ; i++){
                    if(this.items[i].id == this.targetId){
                       this.index_current = i;
                       break;
                    }
                }      

            }
        },
        computed: {
            Selected: function(){
                if(this.items[this.index_current] == undefined){
                    return null;
                }

                return this.paths.media_profiles + this.items[this.index_current].path_file; 
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
                width: 10%;
                float: left;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #b1acac;
                font-size: 150%;
                padding: 5px;
                overflow: hidden; 
                cursor: pointer;
            }

            ._acSelectedPreBox ._acBtnNavigation:hover{
                background: #ecf4ff                               
            }

            ._acSelectedPreBox ._acCurrentImgPreBox{
                width: 80%;
                background-color: #eae3e3;
                position: relative;
                /*padding-top: 100%;*/
                padding-top: 65%; /* 4:3 Aspect Ratio */
                float: left;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;                
            } 

</style>