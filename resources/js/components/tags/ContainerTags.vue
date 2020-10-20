<template>
    <div class="container">
        <span v-if="!isAdd" class="ac-tag">
            <a href="#" v-on:click="onAddNew()">+ Nuevo</a>
        </span>
        <span class="ac-tag" v-else>
            <input type="text" maxlength="25" minlength="4" v-model="nameTag">
            <i  v-on:click="onClickSave()" class="fas fa-save icon"></i>
            <i  v-on:click="onClickCancel()" class="fas fa-times icon"></i>
        </span>
        <etiqueta v-for="(e,index) in tags.slice().reverse()" :key="e.id" @delete="deletetag(index)" :tag="e"></etiqueta>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                tags: [],
                //this is the new tag
                nameTag: '',
                isAdd: false
            }
        },
        mounted() {
            console.log("Me estoy montando y si funciono");
            this.loadData();
        },
        methods: {
            loadData(){
                axios(`/api/tags`).then((result)=>{
                    this.tags =  result.data;
                    console.log(result.data);
                }).catch((ex)=>{
                    console.log("Error ss");
                });
            },
            deletetag(index){
                this.tags.splice(((this.tags.length - 1 )-index),1);
            },
            onAddNew(){
                this.isAdd = true;
            },
            onClickSave(){
                if(this.nameTag.length < 4 || this.nameTag.length.length > 25){
                    console.log("Longitudes no validas");
                    return;
                }
                const params = {
                    nameTag : this.nameTag
                };
                axios.post(`/api/tags`,params).then((result)=>{
                    let res = result.data;
                    if(res.codeStatus === 0){
                        console.log(res.msg);
                    }else{
                        this.tags.push(res.objectData);
                    }
                }).catch((ex)=>{
                    console.log("Se produjo un error! intente mas tarde plox");
                });
            },
            onClickCancel(){
                this.isAdd = false;
            }
        }
    }
</script>
