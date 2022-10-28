<template>
    <div class="card">
        <form class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="wrpp-img-presentation">
                        <img v-if="itemData.promo.image.name != undefined" class="ac_imgAds"
                        :src="itemData.promo.image.data != undefined ? itemData.promo.image.data : itemData.promo.image.url" alt="">
                    </div>
                    <button @click.self.prevent="$refs.filePrincipalPic.click()" class="btn btn-block btn-secondary btn-flat">Subir</button>
                    <input type="file" 
                            accept="image/png, image/jpg, image/jpeg"
                            hidden="true"
                            ref="filePrincipalPic" 
                            @change="setPrincipalPic"  
                            id="inputFileImgPrincipalPic"/> 
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Título</label>
                        <input v-model="itemData.promo.title" type="text" class="form-control" placeholder="Titulo">
                    </div>                    
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Descripción</label>
                        <textarea v-model="itemData.promo.description" class="form-control" id="" cols="30" rows="5" placeholder="Descripción"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Tipo vinculado</label>
                        <select v-model="itemData.promo.type_ads" class="form-control" aria-label="Default select example" id="">
                            <option value="1" selected>Post/Evento</option>
                            <option value="2">Homenajes</option>
                            <option value="3">Recurso</option>
                            <option value="4">Perfil</option>
                        </select>  
                    </div>                    
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Vincular a</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-danger"><i class="fas fa-paste"></i></button>
                            </div>
                            <input v-model="itemData.promo.item_id" type="text" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
            <div class="text-right">
                <button type="button" @click="onSave" class="btn bg-gradient-success btn-flat">Guardar</button>
                <button type="button" @click="onCancel" class="btn bg-gradient-warning btn-flat">Cancelar</button>
            </div>
        </form>

    </div>
</template>

<script>
import {upsertPromo,getPromo} from '@/service';

export default {
    props: {
        pdata: {type: Object, required: true}
    },
    data(){
        return {
            isSaving: false,
            acAppData: window.obj_ac_app,            
            itemData: JSON.parse(JSON.stringify(this.pdata))
        }
    },
    methods: {
        setPrincipalPic: function(event){

            const vm = this;
            if(event.target.files.length > 0){
                const file = event.target.files[0];
                const validExten = ["jpeg","jpg","png"];
                const extenstion = file.name.substring(file.name.lastIndexOf('.')+1, file.name.length) || null;
                if(extenstion == null || !validExten.includes(extenstion.toLowerCase().trim())){
                    StatusHandler.ValidationMsg("Archivos no soportados");
                    return;
                }
                const reader = new FileReader();
                reader.onload =  (e) => {
                    if(e.target.result.substring(0, 10) != "data:image"){
                        StatusHandler.ValidationMsg("Archivos no soportados");
                        return;
                    }
                    vm.setPresentationImg(e.target.result,file.name);
                }
                reader.readAsDataURL(file);
            }
        },
        setPresentationImg: function(base64_img,filename){
            this.itemData.promo.image.name = filename;
            this.itemData.promo.image.data = base64_img;
        },
        onCancel: function(){

        },
        onSave: function(){
            this.upsert();
        },
        upsert: function(){
             this.isSaving = true;
            upsertPromo(this.itemData).then(result => {
                const response= result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    this.isSaving = false;
                    return;
                }                
                this.$emit('on-created',response.data.id);
            }).catch(ex => {
                this.isSaving = false;
                let target_process = "Guarda informacion de elemento"; 
                StatusHandler.Exception(target_process,ex);
            });
        }
    }
}
</script>

<style scoped>
    .wrpp-img-presentation{
        background-color: #3a3838;
        position: relative;
        width: 100%;
        padding-top: 70%; /* 1:1 Aspect Ratio */
    }     

    .ac_imgAds{
        position: absolute;
        top: 0;
        left: 0;
        object-fit: contain; 
        width: 100%;
        height: 100%;

    }
</style>
