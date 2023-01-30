<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="wrpp-img-presentation">
                        <img v-if="itemData.promo.image.name != undefined" class="ac_imgAds"
                        :src="itemData.promo.image.url" alt="">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Título</label>
                        <input disabled v-model="itemData.promo.title" type="text" class="form-control" placeholder="Titulo">
                    </div>                    
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Descripción</label>
                        <textarea disabled v-model="itemData.promo.description" class="form-control" id="" cols="30" rows="5" placeholder="Descripción"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Tipo vinculado</label>
                        <select disabled v-model="itemData.promo.type_ads" class="form-control" aria-label="Default select example" id="">
                            <option value="1" selected>Post/Evento</option>
                            <option value="2">Homenajes</option>
                            <option value="3">Recurso</option>
                            <option value="4">Perfil</option>
                        </select>  
                    </div>                    
                    <div class="form-group">
                        <label for="resourceType" class="form-label">Vincular a</label>
                        <div class="input-group">
                            <input disabled v-model="itemData.promo.item_id" type="text" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
            <div class="text-right">
                <button type="button" @click="onEdit" class="btn bg-gradient-warning btn-flat" :disabled="isDeleting">
                    Editar
                </button>
                <button type="button" @click="onDelete" class="btn bg-gradient-danger btn-flat" :disabled="isDeleting">
                    <span v-if="isDeleting" class="spinner-border spinner-border-sm"  role="status" aria-hidden="true"></span>
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {deletePromo} from '@/service';
export default {
    props: {
        pdata: {type: Object,required:true}
    },
    data: function(){
        return {
             isDeleting: false,
             acAppData: window.obj_ac_app,
             itemData: JSON.parse(JSON.stringify(this.pdata)),
        }
    },
    methods: {
        onEdit: function(){
            this.$emit('on-edit',this.itemData.promo.id);
        },
        onDelete: function(){
            const vm = this;
            Swal.fire({
                title: '¿Está seguro de eliminar?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Eliminar ',
                denyButtonText: `Cancelar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    vm.isDeleting = true;
                    deletePromo(vm.itemData.promo.id).then(result => {
                        const response = result.data;
                        if(response.code == 0){
                            vm.isDeleting = false;
                            StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                            return;
                        }                
                        vm.isDeleting = false;
                        vm.$emit('deleted',vm.itemData.promo.id);
                    }).catch(ex => {
                        StatusHandler.Exception("Eliminar elemento",ex);
                        vm.isDeleting = false;
                    });
                }else{
                    vm.isDeleting = false;
                }
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
