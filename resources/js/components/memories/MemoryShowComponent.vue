<template>
    <div class="pnl-memory card">
        <div class="card-body">
              <div class="row">
                    <h5>Talento/rubro</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="nav-icon fas fa-star-half-alt"></i></span>
                        </div>
                        <input disabled="true" 
                            :value="itemData.memory.area" 
                            type="text" 
                        class="form-control">

                    </div>                     
              </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            itemData: {type: Object,required:true}
        },
        data: function(){
            return {
                acAppData: {}
            }
        },
        mounted() {
            this.acAppData = window.obj_ac_app;
        }
    }
</script>
