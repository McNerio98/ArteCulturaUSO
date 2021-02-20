<template>
                <span class="ac-tag">
                    <input type="text" maxlength="25" minlength="4"
                    :style="{width: getCurrentLength}" v-if="editMode" v-model="tag.name">
                    <span v-else>{{tag.name}}</span>
                    <i v-on:click="onClickEdit($event)" 
                    v-if="!editMode" class="fas fa-pencil-alt icon"></i>
                    <i v-else v-on:click="onClickUpdate()" class="fas fa-save icon"></i>
                    <i v-if="editMode" v-on:click="onCancelEdit()" class="fas fa-times icon"></i>
                    <i v-on:click="onClickDestoy()" class="fas fa-trash-alt icon"></i>
                </span> 
</template>

<script>
    export default {
        props: ["tag"],
        data(){
            return {
                editMode : false,
                prev_value: this.tag.name
            }
        },
        methods: {
            onClickEdit(e){
                this.editMode = true;
                this.prev_value = this.tag_name;
            },
            onCancelEdit(){
                this.editMode = false;
                this.tag.name = this.prev_value;
            },
            onClickUpdate(){
                let size_campo1 = this.tag.name.length;
                if(size_campo1 < 1  || size_campo1 > 50){
                    StatusHandler.ValidationMsg("Nombre de etiqueta no valido");
                    return;
                }
                const params = {
                    tag_name : this.tag.name, 
                }; 
                this.editMode = false; //se bloquea otro click 
                axios.put(`/api/tags/${this.tag.id}`,params).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        this.tag.name = prev_value;
                        return;
                    };
                    this.tag = response.data;
                }).catch((ex)=>{
                    this.tag.name = prev_value;
                     StatusHandler.Exception("Actualizar Etiqueta/Rubro",ex);
                });
                
            },
            onClickDestoy(){
                this.editMode = false;
                axios.delete(`/api/tags/${this.tag.id}`).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        this.tag.name = prev_value;
                        return;
                    };
                    this.$emit('delete');
                    console.log("Etiqueta eliminada");
                }).catch((ex)=>{
                     StatusHandler.Exception("Eliminar Etiqueta/Rubro",ex);
                });
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