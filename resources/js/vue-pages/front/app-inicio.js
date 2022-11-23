Vue.component('request-component', require('../../components/RequestAccount.vue').default);
Vue.component('search-component', require('../../components/search/SearchComponent.vue').default);
Vue.component('summary-item',require('../../components/post/PostEventCardComponent.vue').default);




import {getElementoTablero} from '@/service';
import {formatter88} from '@/formatters';
import PostEventCard2Component from '@/components/post/PostEventCard2Component.vue'
import TableroPostEventCad from '@/components/tablero/PostEventCard.vue';


const app_inicio = new Vue({
    el: '#app_inicio',
    components: {
        'posttable-style': PostEventCard2Component,
        'table-event': TableroPostEventCad
    },
    data: {
        isLoading: false,
        acAppData: window.obj_ac_app,
        events: []
    },
    mounted: function(){
        const params = {
            start_date: null,
            items_limit: 4
        };

        this.loadTableEvents(params);
    },
    computed: {
    },
    methods: {      
        onSeeMore: function(id){
            window.location.replace(this.acAppData.base_url + `/postshow/${id}`);
        },
        loadTableEvents: function(params){
            getElementoTablero(params).then(result => {
                const response = result.data;
                if(response.code == 0){
                    this.isLoading = false;
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }     

                this.events = response.data.map(e => {
                    e.media = [];
                    return formatter88(e, this.acAppData.storage_url);
                })

            }).catch(ex => {
                const target_process = "Recuperar elementos"; 
                StatusHandler.Exception(target_process,ex);
            });
        },
        exeSeach: function(ng){
            if(ng.id_filter == undefined || ng.label == undefined || ng.type_search == undefined){
                alert("Error");
                return;
            }
            window.location.href =  this.acAppData.base_url+`/search?id_filter=${ng.id_filter}&label=${ng.label}&type_search=${ng.type_search}`;
        },
        onClickEvent: function(event_el){
            window.location.href = this.acAppData.base_url + `/events?target=${event_el.id}`;
        },
        onShowPromo(id,typeads){
            const validTypes = [1,2,3,4];
            var redirect = "";
            if(!validTypes.includes(typeads)){
                alert("Inconsistencia en los tipos de promociones");
                return;
            }

            switch(typeads){
                case 1: {//PostEvent 
                    redirect = this.acAppData.base_url + `/postshow/${id}`;
                    break;
                }
                case 2: {//Homenajes/Biografias
                    redirect = this.acAppData.base_url + `/site/biografias/${id}`;
                    break;
                }
                case 3: {//Recurso
                    redirect = this.acAppData.base_url + `/site/recursos/${id}`;
                    break;
                }
                case 4: {//Perfil
                    redirect = this.acAppData.base_url + `/perfil/${id}`;
                    break;
                }                                                
            }
            window.location.href = redirect;
        }
    }
});