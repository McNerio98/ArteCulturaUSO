<template>
    <div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static">
                <strong class="d-inline-block mb-2 text-primary">{{itemData.memory.area}}</strong>
                <h3 class="mb-0">{{itemData.memory.name}}</h3>
                <div class="mb-1 text-muted">{{itemData.memory.birth_date | DateFormatES2}}</div>
                <p class="card-text mb-auto" v-html="itemData.memory.content"></p>
                <a :href="acAppData.base_url + '/' + 'admin/memories/' +itemData.memory.id" class="stretched-link">Seguir leyendo</a>

            </div>
            <div class="col-auto d-none d-lg-block">
                <div class="bg-dark container-embed-img item1-img" style="width: 200px; height: 250px; background-image: url('../img/artista1.jpg')">
                    <div class="tenue">                    
                    </div>
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
                acAppData: {},
                flags: {}
            }
        },
        created: function(){
            this.acAppData = window.obj_ac_app;
        }
    }
</script>
