<div class="row tblro__ac-container">
    <div class="tblro__ac-opnearby">
        <a href="{{route('nearby')}}">
            <img src="{{asset('images/icons/gps-svgrepo-com.svg')}}" alt=""/>
                <span>Eventos cercanos</span>
        </a>
    </div>

    <div class="tblro__ac-item-container">

        <div class="tblro__ac-item" v-for="(e,index) in events" :data-pos="index" :class="classHidden(index)" @click="onSeeMore(e.post.id)">
            <a href="javascript:void;" >
                <div class="tblro__ac-item-a">

                <div v-if="e.presentation_model == undefined || e.presentation_model.type_file == 'docfile'" class="bg-dark" style="height: 100%; display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                    <span class="text-muted">Sin Imagen</span>
                </div>
                <!--IF THE PRESENTATION IMG IS IMAGE OR VIDEO-->
                <template v-if="e.presentation_model != undefined && (e.presentation_model.type_file == 'image' || e.presentation_model.type_file == 'video')" class="bg-dark" style="height: 200px;">                    
                    <img style="object-fit: contain; padding-top: 3px; width: 100%;height: 100%;" alt="" :src="e.presentation_model.url">
                </template>    

                </div>
                <div class="tblro__ac-item-b p-1 p-md-2">                    
                    <p class="m-0 p-0 _noWrp text-dark font-weight-bold">
                        <span>@{{e.post.title}}</span>
                    </p>
                    <p class=" m-0 p-0 _noWrp text-dark text-success">
                        <span>@{{e.dtl_event.event_date | DateFormatES2}}</span>
                    </p>                           
                    <p class="mb-0 mb-md-1">
                        <i class="fas fa-map-marker-alt"></i> 
                            Lugar  
                            <span class="badge  bg-success">
                            @{{e.dtl_event.address.details}}
                            </span>
                    </p>
                    <p class="mb-0 mb-md-1">
                            <i class="fas fa-dollar-sign"></i> 
                            Entrada 
                            <span class="badge  bg-success">
                            @{{e.dtl_event.has_cost == true ? "$ " + e.dtl_event.cost : "GRATIS"}}
                            </span>                    
                    </p>
                    <p class="mb-0 mb-md-1">
                            <i class="fas fa-redo-alt"></i>
                            Se repite 
                            <span class="badge  bg-success">
                            @{{e.dtl_event.frequency == "unique" ? 'FECHA ÚNICA ' : 'CADA AÑO'}}
                            </span>                    
                    </p>  
                </div>
            </a>
        </div>                                  
    </div>

    <div class="tblro__ac-left">
        <button class="tblro__ac-btns">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>  
    <div class="tblro__ac-rigth">
        <button class="tblro__ac-btns">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>            
</div>
