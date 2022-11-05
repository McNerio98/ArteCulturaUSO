<template>
    <div class="tblro__ac-item">
        <a href="javascript:void;" >
            <div class="tblro__ac-item-a">
                <div v-if="itemData.presentation_model == undefined || itemData.presentation_model.type_file == 'docfile'" class="bg-dark" style="height: 100%; display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                    <span class="text-muted">Sin Imagen</span>
                </div>
                    <!--IF THE PRESENTATION IMG IS IMAGE OR VIDEO-->
                    <template v-if="itemData.presentation_model != undefined && (itemData.presentation_model.type_file == 'image' || itemData.presentation_model.type_file == 'video')" class="bg-dark" style="height: 200px;">                    
                        <img style="object-fit: contain; padding-top: 3px; width: 100%;height: 100%;" alt="" :src="itemData.presentation_model.url">
                    </template>                    
            </div>
            <div class="tblro__ac-item-b p-1 p-md-2">
                <p class="m-0 p-0 _noWrp text-dark font-weight-bold">
                    <span>{{itemData.post.title}}</span>
                </p>
                <p class=" m-0 p-0 _noWrp text-dark text-success">
                    <span>@{{itemData.dtl_event.event_date | DateFormatES2}}</span>
                </p>                           
                <p class="mb-0 mb-md-1">
                    <i class="fas fa-map-marker-alt"></i> 
                    Lugar  
                    <span class="badge  bg-success">
                        {{itemData.dtl_event.address.details}}
                    </span>
                </p>
                <p class="mb-0 mb-md-1">
                    <i class="fas fa-dollar-sign"></i> 
                    Entrada 
                    <span class="badge  bg-success">
                        @{{itemData.dtl_event.has_cost == true ? "$ " + itemData.dtl_event.cost : "GRATIS"}}
                    </span>                    
                </p>
                <p class="mb-0 mb-md-1">
                    <i class="fas fa-redo-alt"></i>
                    Se repite 
                    <span class="badge  bg-success">
                        @{{itemData.dtl_event.frequency == "unique" ? 'FECHA ÚNICA ' : 'CADA AÑO'}}
                    </span>                    
                </p>                                                 
            </div>
        </a>
    </div>
</template>
<script>
export default {
    props: {
        pdata: {type: Object,required:true}
    },
    data: function(){
        return {
            itemData: JSON.parse(JSON.stringify(this.pdata)),
            acAppData: window.obj_ac_app,            
        }
    }
}

</script>
