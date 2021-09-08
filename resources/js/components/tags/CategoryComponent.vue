<template>
      <div  class="border-bottom rounded p-1 p-md-3 ac-item-cat"
      @click="$emit('select-item',item)"
       v-bind:class="[selected.id == item.id?'active':'bg-light']">

            <span v-if="!edit_mode">{{item.name}}</span>
            <input type="text" v-if="edit_mode" v-model="item.name">

            <i v-if="item.id != 9 && item.id != 10 && !edit_mode" @click="edit_mode = true" class="fas fa-pencil-alt icon i-edit"></i>
            <i v-if="item.id != 9 && item.id != 10 && !edit_mode" class="fas fa-trash-alt icon i-del"></i>          
    </div>
</template>

<script>
    export default {
        props: ["model","selected"],
        data: function(){
            return {
                item: {},
                edit_mode: false
            }
        },
        mounted: function(){
            this.item = this.model;
        }
    }
</script>
