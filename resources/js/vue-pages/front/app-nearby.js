
import GpsComponent from '../../components/nearby/GpsComponent.vue';
import Spinner1Component from '../../components/spinners/Spinner1Component.vue';
import PostEventCard from '../../components/post/PostEventCardComponent.vue';
import {getNearbyPostEvents} from '../../service';
import {formatter88} from '../../formatters';
import {municipiosItems} from '../../utils';


const appNearbyFront = new Vue({
    el: "#appNearbyFront",
    components: {
        'gps-request': GpsComponent,
        'spinner1': Spinner1Component,
        'postevent-card' : PostEventCard
    },
    data: {
        acAppData: window.obj_ac_app,
        currentGeo: {
            lat: 0,
            lng: 0
        },
        isRequestActive: true,
        isGettingItems: false,
        itemsNearby: [], //candidatos 
        finalNearby: [],
        radio: {
            enabled: true,
            limit: 1000 //En kilometros 
        }
    },
    created: function(){
        this.municipios = municipiosItems();
    },
    methods: {
        onSelected: function(id){
            window.location.replace(this.acAppData.base_url + `/postshow/${id}`);
        },
        rad: function(x){
            return x * Math.PI / 180;
        },
        distanciaMetros: function(lat1, lon1, lat2, lon2){
            const R = 6375.137; //Radio de la tierra en km 
            const dLat = this.rad(lat2 - lat1);
            const dLong = this.rad(lon2 - lon1);
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(this.rad(lat1)) * Math.cos(this.rad(lat2)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const d = R * c * 1000;
            return d;
        },
        calcularYOrdenar: function(){
            var menor = 9999999;
            var index_menor = -1;
            var radio_bsc = 12000;    
            var candidatos = [];

            this.itemsNearby.forEach((item,index) =>{
                if(item.event_detail.is_geo == 1){
                    let calculado = this.distanciaMetros(
                        this.currentGeo.lat,
                        this.currentGeo.lng,
                        item.event_detail.geo_lat,
                        item.event_detail.get_lng);
                    const distancia = isNaN(parseInt(calculado)) ? 0 : parseInt(calculado);
                    candidatos.push({
                        indice: index,
                        distancia: distancia
                    });                    
                }
            });

            //Ordenar de manor distancia a mayor distancia desde el origen 
            const ordenados = candidatos.sort((a,b) => (a.distancia > b.distancia) ? 1: -1);
            //Aqui aplicar filtro de radio menor aceptado 
            this.mostrarNearby(ordenados);
        },
        mostrarNearby: function(ordenados){
            ordenados.forEach((item,index)=>{
                //const municipio = this.municipios[municipio_id-1].municipio;
                this.itemsNearby[item.indice].media = [];
                this.finalNearby.push(
                    formatter88(
                        {...this.itemsNearby[item.indice]},
                        this.acAppData.base_url
                    ),
                ); //rompiendo referencia
            });
        },
        getDataNearby: function(){
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
                this.calcularYOrdenar();
            }).catch(ex =>{
                this.isGettingItems = false;
                let target_process = "Recuperar elementos cercanos"; 
                StatusHandler.Exception(target_process,ex);
            });
        },
        onActive: function(position){
            this.currentGeo.lat = position.coords.latitude;
            this.currentGeo.lng = position.coords.longitude;        
            this.isRequestActive = false;
            this.getDataNearby();
        },
        onDenied: function(ex){
            window.location.reload();
        }
    }
});