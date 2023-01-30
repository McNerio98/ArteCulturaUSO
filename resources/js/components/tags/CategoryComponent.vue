<template>
      <div  class="border-bottom rounded p-1 p-md-3 ac-item-cat"
      @click="$emit('select-item',itemData)"
       v-bind:class="[selected.id == itemData.id?'active':'bg-light']">

            <span v-if="!edit_mode">{{itemData.name}}</span>


            <div class="input-group" v-if="edit_mode">
                <input type="text" class="form-control" v-model="itemData.name" placeholder="Nombre de categoria" :disabled="isSaving">
                <div class="input-group-append" id="button-addon4">
                    <button class="btn btn-success" type="button" @click="onClickConfirmUpdate" :disabled="isSaving">
                        <i class="fas fa-save icon"></i>
                    </button>
                    <button class="btn btn-warning" type="button" @click="onClickCancelUpdate" :disabled="isSaving">
                        <i class="fas fa-times icon"></i>
                    </button>
                </div>
            </div>
            <!---Icono de editar -->
            <i v-if="(itemData.id != 9 && itemData.id != 10 && !edit_mode) && has_cap('editar-rubros')" 
                @click="onClickEdit" class="fas fa-pencil-alt icon i-edit">
            </i>
            <!---Icono de Eliminar -->
            <i v-if="(itemData.id != 9 && itemData.id != 10 && !edit_mode) && has_cap('eliminar-rubros')" 
                @click="onClickDestroy" class="fas fa-trash-alt icon i-del">
            </i>
    </div>
</template>

<script>
    import {upsertCategory , deleteCategory} from '@/service';

    export default {
        props: ["pdata","selected"],
        data: function(){
            return {
                edit_mode: false,
                isSaving: false,
                backup_name: "",
                itemData: JSON.parse(JSON.stringify(this.pdata))
            }
        },
        methods: {
            onClickEdit: function(){
                this.backup_name = this.itemData.name;
                this.edit_mode = true;
            },
            onClickDestroy: async function(){
                const confirmUser = await StatusHandler.confirm(
                    '¿Está usted seguro?',
                    '(NO RECOMENDADO) Esta operación removerá todas las etiquetas asociadas a esta categoría; por consiguiente removerá las etiquetas dentro de los usuarios que la han asociado. Procesa solo si está realmente seguro.'
                );

                if(!confirmUser){this.onCancelEdit();return;}                 
                
                deleteCategory(this.itemData.id).then(result => {
                    const response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    };
                    this.$emit('deleted');
                }).catch(ex => {
                    StatusHandler.Exception("Eliminar categoría",ex);
                });
            },
            onClickConfirmUpdate: async function(){
                const  nameLength = this.itemData.name.length;
                if(nameLength < 1  || nameLength > 50){
                    StatusHandler.ValidationMsg("Longitud de categoria no valida (1-50)");
                    return;
                }

                const confirmUser = await StatusHandler.confirm(
                    '¿Está usted seguro?',
                    'El nuevo nombre de la categoría aparecerá dentro de las búsquedas'
                );
                if(!confirmUser){
                    this.onClickCancelUpdate();
                    return;
                }                

                const payload = {
                    category_id: this.itemData.id,
                    category_name: this.itemData.name
                }

                this.isSaving = true;
                upsertCategory(payload).then(result => {
                    const response = result.data;
                    if(response.code == 0){
                        this.isSaving = false;
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        this.onClickCancelUpdate();
                        return;
                    };

                    this.isSaving = false;
                    this.edit_mode = false;
                }).catch(ex => {
                    this.isSaving = false;
                    StatusHandler.Exception("Guardar cambios en categoria",ex);
                });

            },
            onClickCancelUpdate: function(){
                this.edit_mode = false;
                this.itemData.name = this.backup_name;
            },
            has_cap(e){
                return window.has_cap == undefined ? false : window.has_cap(e);
            }                      
        },
        mounted: function(){

        }
    }
</script>
