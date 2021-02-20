<template>
  <li class="item ad-popular-pe">
    <div class="product-img">
      <img
        :src="path_file"
        alt="Product Image"
        class="img-size-50"
      /> 
    </div>
    <div class="product-info">
      <a href="javascript:void(0)" class="product-title"
        ><i class="fas fa-star" v-if="is_popular"></i> {{title}}
        <span class="badge  float-right" v-bind:class="[type_post=='post' ? 'badge-info':'badge-warning']" >{{type_post}}</span></a
      >
      <span class="product-description">
          {{description}}
      </span>
    </div>
  </li>
</template>

<script>
export default {
    props: ['infoObj'],
    data: function(){
        return{
            id: 0,
            path_file: null,
            is_popular: false,
            title: null,
            description: null,
            type_file: null,
            type_post: null
        }
    },
    mounted(){
        if(this.infoObj != undefined){
            this.id = this.infoObj.id;
            this.path_file = this.infoObj.path_file != null ? "/" + this.infoObj.path_file + this.infoObj.name: "../images/no_image_found.png";
            this.is_popular = this.infoObj.is_popular;
            this.title = this.infoObj.title;
            this.description = this.infoObj.content;
            this.type_file = this.infoObj.type_file;
            this.type_post = this.infoObj.type_post=="post"?"post":"evento";
        }
    }
}
</script>