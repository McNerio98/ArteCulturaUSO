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
                backupVal: this.tag.name
            }
        },
        mounted() {
            console.log('Ciclo de vida del componente.')
        },
        methods: {
            onClickEdit(e){
                this.editMode = true;
                console.log(this.backupVal);
            },
            onCancelEdit(){
                this.editMode = false;
            },
            onClickUpdate(){
                //validation 
                let lon = this.tag.name.length;
                if(lon < 3 || lon > 20){
                    console.log("Error no longitud no valida");
                    return;
                }
                const params = {
                    new_name : this.tag.name, 
                }; 
                this.editMode = false;
                console.log("Nuevo valor: " + params.new_name);

                axios.put(`/api/tags/${this.tag.id}`,params).then((result)=>{
                    let res = result.data;
                    if(res.codeStatus === 0){
                        console.log(res.msg);
                    }
                }).catch((ex)=>{
                    this.tag.name = this.backupVal;
                    console.log("Se produjo un error! intente mas tarde");
                });
                
            },
            onClickDestoy(){
                this.editMode = false;
                axios.delete(`/api/tags/${this.tag.id}`).then((result)=>{
                    let res = result.data;
                    console.log(res);
                    if(res.codeStatus === 0){
                        console.log(res.msg);
                    }else{
                        this.$emit('delete');
                    }
                }).catch((ex)=>{
                    this.tag.name = this.backupVal;
                    console.log("Se produjo un error! intente mas tarde");
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