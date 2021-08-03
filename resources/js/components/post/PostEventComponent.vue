<template>
    <div class="col-md-4" style="padding-left:15px !important;padding-right: 15px !important;">
        <div class="card mb-4 shadow-sm">
            <div class="card-header text-success border">
                <h5><i class="far fa-clock"></i><span> {{model.creator.nickname}}</span></h5>
            </div>
                <div class="bg-dark _galHero" style="height: 200px;" :style="{backgroundImage: 'url(' + model.presentation_img + ')'}">
            </div>
            <div class="card-body">
                <a class="text-muted" @click="emitSelectedItem" href="javascript:void(0);">{{model.title}}</a>
                <p class="card-text">{{model.description}}<a href=""><u> Ver mas</u></a> </p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-share-alt"></i>
                            Compartir</button>
                    </div>
                    <span class="text-muted"><i v-bind:class="{'fas fa-star': model.is_popular, 'far fa-star': !model.is_popular}"></i></span>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    ._galHero{
        background-color: #dad8d8;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;    
        border: 1px solid white;
    }
</style> 

<script>
    export default {
        props: {
            model: {
                type: Object,
                default: function(){
                    return {
                        id:0,
                        title: "",
                        description: "",
                        type_item: "post",
                        presentation_img: null,
                        is_popular: false,
                        dtl_event: {
                            event_date: "",
                            has_cost: false,
                            cost: 0,
                            frequency: "unique"
                        },
                        creator: {
                            id: 0,
                            name: ""
                        }
                    }
                }
            }
        },
        mounted() {
            console.log('Ciclo de vida del componente.')
        },
        methods: {
            emitSelectedItem(){
                let retdata = {id: this.model.id};
                this.$emit('selected-item',retdata);
            }
        } 
    }
</script>
