<template>
    <div class="col-md-4">
        <a href="javascript:void;"   class="card mb-4 shadow-sm" @click.prevent="onSeeMore">
            <div class="card-header border p-1">
                <p class="m-0 p-0 _noWrp text-dark font-weight-bold">
                    <span> {{itemData.post.title}}</span>
                </p>
                <p class=" m-0 p-0 _noWrp text-dark text-success">
                    <span> {{itemData.dtl_event.event_date | DateFormatES2}}</span>
                </p>                
            </div>    
            <!--IF THE PRESENTATION IMG IS PDF OR UNDEFINED-->
            <div v-if="itemData.presentation_model == undefined || itemData.presentation_model.type_file == 'docfile'" class="bg-dark" style="height: 200px; display: flex;flex-direction: column;justify-content: center;align-items: center;">
                <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                <span class="text-muted">Sin Imagen</span>
            </div>
            <!--IF THE PRESENTATION IMG IS IMAGE OR VIDEO-->
            <div v-if="itemData.presentation_model != undefined && (itemData.presentation_model.type_file == 'image' || itemData.presentation_model.type_file == 'video')" class="bg-dark" style="height: 200px;">                    
                <img style="object-fit: contain; padding-top: 3px; width: 100%;height: 100%;" alt="" :src="itemData.presentation_model.url">
            </div>      
            <div class="card-body p-1 p-md-2" style="background-color: #e1dede !important;">
                <p class="mb-0 mb-md-1 _noWrp">
                    <span class="text-dark font-weight-bold">
                        <i class="fas fa-map-marker-alt"></i> 
                        Lugar  
                    </span>
                    <span class="text-success font-weight-bold">
                        {{getDirection()}}
                    </span>
                </p>
                <p class="mb-0 mb-md-1 _noWrp">
                    <span class="text-dark font-weight-bold">
                        <i class="fas fa-dollar-sign"></i> 
                        Entrada
                    </span> 
                    <span class="text-success font-weight-bold">
                        {{itemData.dtl_event.has_cost == true ? "$ " + itemData.dtl_event.cost : "GRATIS"}}
                    </span>                    
                </p>
                <p class="mb-0 mb-md-1 _noWrp">
                    <span class="text-dark font-weight-bold">
                        <i class="fas fa-redo-alt"></i>
                        Se repite 
                    </span>
                    <span class="text-success font-weight-bold">
                        {{itemData.dtl_event.frequency == "unique" ? 'FECHA ÚNICA ' : 'CADA AÑO'}}
                    </span>                    
                </p>                
            </div>              
        </a>
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
    import { getMunicipioById, getDeptoById } from "@/utils";
    export default {
        props: {
            pdata: {type: Object,required:true},
            authId: {type: Number, default: 0}//Current user id 
        },
        data: function(){
            return {
                itemData: JSON.parse(JSON.stringify(this.pdata)),
                acAppData: window.obj_ac_app,
            }
        },
        methods: {
            onSeeMore: function(){
                this.$emit('selected',this.itemData.post.id);
            },
            getDirection: function(){
                return this.itemData.dtl_event.address.details +
                ", " + getMunicipioById(this.itemData.dtl_event.address.municipio_id) +
                ", " + getDeptoById(this.itemData.dtl_event.address.depto_id);
            }            
        } 
    }
</script>
