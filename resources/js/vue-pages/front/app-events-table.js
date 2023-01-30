Vue.component('spinner1',require('@/components/spinners/Spinner1Component.vue').default);
import TableroPostEventCad from '@/components/tablero/PostEventCard.vue';
import TableroLoadMore from '@/components/tablero/MoreContentCard.vue';
import {getElementoTablero} from '@/service';
import {formatter88} from '@/formatters';
import NoDataCustom from '@/components/NoDataCustom.vue';

const appEvents = new Vue({
    el: "#appEventsTable",
    components: {
        'table-event': TableroPostEventCad,
        'table-load-more' : TableroLoadMore,
        'nodata-custom' : NoDataCustom
    },
    data: {
        spinners: {
            S1: false
        },
        isEnableMore: true, //cambiar aqui
        events: [],
        isGettingData: true,
        acAppData: window.obj_ac_app
    }, 
    mounted: function(){
        const params = {
            start_date: null,
        };

        this.loadTableEvents(params);
    },
    methods: {
        onLoadMore: function(){
            const params = {
                start_date: null,
            };

            //Tomar la fecha del ultimo 
            params.start_date = this.events[this.events.length - 1].dtl_event.event_date;

            this.loadTableEvents(params);
        },
        loadTableEvents: function(params){
            this.isGettingData = true;
            getElementoTablero(params).then(result => {
                const response = result.data;
                if(response.code == 0){
                    this.isGettingData = false;
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }     
                
                var contador = 0;
                 response.data.map(e => {
                    e.media = [];
                    this.events.push(formatter88(e, this.acAppData.storage_url));
                    contador++;
                });

                if(contador == 0){
                    this.isEnableMore = false;
                }

                this.isGettingData = false;
            }).catch(ex => {
                this.isGettingData = false;
                const target_process = "Recuperar elementos"; 
                StatusHandler.Exception(target_process,ex);
            });
        },
        checkParam: function(){
            var target_item = parseInt($("#targetOpenItem").val());
            target_item =  isNaN(target_item) ? 0 : target_item;
            if(target_item != 0){
                this.loadTarget(target_item);
            }
        },
        onSources: function(){

        },
        onSeeMore: function(id){
            window.location.href = this.acAppData.base_url + `/postshow/${id}`;
        },
        onClickEvent: function(event_el){
            this.loadTarget(event_el.id);
        },

    }
});