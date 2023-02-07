<template>
                <span class="ac-tag">
                    <input type="text" maxlength="25" minlength="4"
                    :style="{width: getCurrentLength}" v-if="editMode" v-model="tag.name">
                    <span v-else>{{tag.name}}</span>
                    <i v-on:click="onClickEdit($event)" v-if="!editMode && has_cap('editar-rubros')" class="fas fa-pencil-alt icon"></i>
                    <!--Opcion confirmar edicion de la edicion actual-->
                    <i v-if="editMode" v-on:click="onClickUpdate()" class="fas fa-save icon"></i>
                    <!--Opcion (X) para cancelar edicion-->
                    <i v-if="editMode" v-on:click="onCancelEdit()" class="fas fa-times icon"></i>
                    <!--Opcion para eliminar etiqueta-->
                    <i v-if="has_cap('eliminar-rubros')" v-on:click="onClickDestoy()" class="fas fa-trash-alt icon"></i>
                </span> 
</template>

<script>
    import {updateTag , deleteTag} from '@/service';
    
    export default {
        props: ["ptag"],
        data(){
            return {
                editMode : false,
                tag: JSON.parse(JSON.stringify(this.ptag))
            }
        },
        methods: {
            onClickEdit(e){
                this.editMode = true;
                this.prev_value = this.tag.name;
                console.log(this.prev_value);
            },
            onCancelEdit(){
                this.editMode = false;
                this.tag.name = this.prev_value;
            },
            onClickUpdate: async function(){
                let size_campo1 = this.tag.name.length;
                if(size_campo1 < 1  || size_campo1 > 50){
                    StatusHandler.ValidationMsg("Longitud de etiqueta no valido (2-50)");
                    return;
                }
                const params = {
                    tag_name : this.tag.name, 
                }; 

                const confirmUser = await StatusHandler.confirm(
                    '¿Está usted seguro?',
                    '(NO RECOMENDADO) Esta operación afectará a todos los usuarios que tengan esta etiqueta'
                );
                if(!confirmUser){
                    this.onCancelEdit();
                    return;
                }

                this.editMode = false; //se bloquea otro click 
                //console.log("Este sera el valor del backup " + this.prev_value);
                updateTag(this.tag.id,params).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        this.tag.name = this.prev_value;
                        return;
                    };
                    this.tag = response.data;
                }).catch((ex)=>{
                    this.tag.name = this.prev_value;
                     StatusHandler.Exception("Actualizar Etiqueta/Rubro",ex);
                });
                
            },
            onClickDestoy: async function(){
                const confirmUser = await StatusHandler.confirm(
                    '¿Está usted seguro?',
                    '(NO RECOMENDADO) Esta operación removerá esta etiqueta a todos los usuarios que la tengan incluida, procesa solo si está realmente seguro.'
                );

                if(!confirmUser){this.onCancelEdit();return;}                

                this.editMode = false;
                deleteTag(this.tag.id).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    };
                    this.$emit('delete');
                }).catch((ex)=>{
                     StatusHandler.Exception("Eliminar Etiqueta/Rubro",ex);
                });
            },
            has_cap(e){
                return window.has_cap == undefined ? false : window.has_cap(e);
            }                   
        },
        computed: {
            //propiedads que se calculan al instante
            getCurrentLength: function(){
                return ((this.tag.name.length + 1) * 8) + 'px';
            } 
        }
    }
</script>