<template>

    <div class="col-md-4" style="padding-left:15px !important;padding-right: 15px !important;">
        <div class="card mb-4 shadow-sm">
            <div class="card-header border p-1">
                <h5 class="m-0 p-0 _noWrp"><i class="fas fa-bell"></i><span> {{model.creator.nickname}}</span></h5>
            </div>
                <!--IF THE PRESENTATION IMG IS PDF OR UNDEFINED-->
                <div v-if="model.presentation_type == undefined || model.presentation_type == 'docfile'" class="bg-dark" style="height: 200px; display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <i class="fas fa-image text-muted " style="font-size: 2rem;"></i>
                    <span class="text-muted">Sin Imagen</span>
                </div>
                <!--IF THE PRESENTATION IMG IS IMAGE OR VIDEO-->
                <div v-if="model.presentation_type == 'image' || model.presentation_type == 'video'" class="bg-dark" style="height: 200px;">                    
                    <img style="object-fit: contain; padding-top: 3px; width: 100%;height: 100%;" alt="" :src="model.presentation_img">
                </div>
            <div class="card-body p-1">
                <!-- <a class="text-muted" @click="emitSelectedItem" href="javascript:void(0);">{{model.title}}</a> -->
                <p class="card-text"> {{ (model.title.length >= 70) ? model.title.substring(0,70) + " ... " : model.title }} <a href="javascript:void(0);" @click="emitSelectedItem"> <u> Ver mas</u></a> </p>
                <ul class="nav flex-column" v-if="model.type === 'event'">
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link p-0">
                            Fecha <span class="float-right badge  bg-success">{{model.dtl_event.event_date | DateFormatES2}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link p-0">
                            Entrada 
                            <span class="float-right badge  bg-success">
                                {{model.dtl_event.has_cost == true ? "$ " + model.dtl_event.cost : "GRATIS"}}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link p-0">
                            Tipo 
                            <span class="float-right badge bg-success">
                                {{model.dtl_event.frequency == 'unique' ? 'Evento único' : 'Se repite cada año'}}
                            </span>
                        </a>
                    </li>
                </ul>                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <!-- <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-share-alt"></i>
                            Compartir</button> -->
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

    ._noWrp{
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;        
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
            
        },
        methods: {
            emitSelectedItem(){
                let retdata = {id: this.model.id};
                this.$emit('selected-item',retdata);
            }
        } 
    }
</script>
