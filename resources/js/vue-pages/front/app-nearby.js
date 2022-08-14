
import GpsComponent from '../../components/nearby/GpsComponent.vue';
import Spinner1Component from '../../components/spinners/Spinner1Component.vue';
import {getNearbyPostEvents} from '../../service';

const appNearbyFront = new Vue({
    el: "#appNearbyFront",
    components: {
        'gps-request': GpsComponent,
        'spinner1': Spinner1Component
    },
    data: {
        isRequestActive: true,
        isGettingItems: false,
        itemsNearby: []
    },
    methods: {

        getDataNearby: function(lat,lng){
            this.isGettingItems = true;
            getNearbyPostEvents().then(result=>{
                const response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                
                //Formatear, falta, considerar usar el queya estaba o hacer uno mas liviano
                this.itemsNearby = response.data;
                this.isGettingItems = false;
            }).catch(ex =>{
                this.isGettingItems = false;
                let target_process = "Recuperar elementos cercanos"; 
                StatusHandler.Exception(target_process,ex);
            });
        },
        onActive: function(position){
            const latitude  = position.coords.latitude;
            const longitude = position.coords.longitude;            
            this.isRequestActive = false;
            this.getDataNearby(latitude,longitude);
        },
        onDenied: function(ex){
            window.location.reload();
        }
    }
});