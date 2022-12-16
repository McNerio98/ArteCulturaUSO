<template>
    <div class="ac_tbl-item-wrp" @click="onSelected">
        <div class="ac_tbl-linetime-wrp">
            <div class="ac_tbl-linetime-point">
                <div class="ac_tbl-event1Bubble">
                    <div class="ac_tbl-eventTime">
                        <div class="ac_tbl-DayDigit">{{itemData.dtl_event.event_date|DateFormatDay}}</div>
                            <div class="ac_tbl-Day">
                                {{itemData.dtl_event.event_date|DateFormatDayName}}
                                <div class="ac_tbl-MonthYear">{{itemData.dtl_event.event_date|DateFormatMonthName}} {{itemData.dtl_event.event_date|DateFormatYear}}</div>
                            </div>
                    </div>
                    <div class="ac_tbl-eventTitle">{{itemData.dtl_event.event_date|DateFormatTime}} </div>
                </div>
                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="8" fill="#004165"></circle>
                </svg>    
            </div>
            <div class="ac_tbl-line"></div>
        </div>
        <div class="ac_tbl-item-content">
            <div class="ac_tbl-item-card">

                <!--IF THE PRESENTATION IMG IS PDF OR UNDEFINED-->
                <div v-if="itemData.presentation_model == undefined || itemData.presentation_model.type_file == 'docfile'" class="bg-dark ac_tbl-item-card-no-img">
                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                    <span class="text-muted">Sin Imagen</span>
                </div>           
                <!--IF THE PRESENTATION IMG IS IMAGE OR VIDEO-->
                <div v-if="itemData.presentation_model != undefined && (itemData.presentation_model.type_file == 'image' || itemData.presentation_model.type_file == 'video')" class="bg-dark ac_tbl-item-card-img"> 
                    <img style="object-fit: contain; padding-top: 3px; width: 100%;height: 100%;" alt="" :src="itemData.presentation_model.url">
                </div> 

                <div class="ac_tbl-item-card-desc">
                    <p><i class="fas fa-map-marker-alt"></i> Lugar :  <span class="text-success">{{getDirection()}}</span></p>
                    <hr class="mt-2 mb-2"/>
                    <h5>{{itemData.post.title}}</h5>
                </div>
            </div>                    
        </div>
    </div>
</template>

<script>
    import { getMunicipioById, getDeptoById } from "@/utils";
    export default {
        props: {
            pdata: {type: Object,required:true}
        },
        data: function(){
            return {
                itemData: JSON.parse(JSON.stringify(this.pdata))                   
            }
        },
        methods: {
            onSelected: function(){
                this.$emit('on-show',this.itemData.post.id);
            },
            getDirection: function(){
                return this.itemData.dtl_event.address.details +
                ", " + getMunicipioById(this.itemData.dtl_event.address.municipio_id) +
                ", " + getDeptoById(this.itemData.dtl_event.address.depto_id);
            }
        }
    }
</script>
