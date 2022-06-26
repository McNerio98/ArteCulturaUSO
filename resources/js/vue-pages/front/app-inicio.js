Vue.component('request-component', require('../../components/RequestAccount.vue').default);
Vue.component('search-component', require('../../components/search/SearchComponent.vue').default);
Vue.component('summary-item',require('../../components/post/PostEventCardComponent.vue').default);

const app_inicio = new Vue({
    el: '#app_inicio',
    data: {
        acAppData: {},
        events: [] //only 3 elements 
    },
    mounted: function(){
        this.loadEvents();
        this.acAppData = window.obj_ac_app;
    },
    methods: {  
        loadEvents: function(){
            /* init_pagination=false porque solo necesitamos 3 elementos, los demas se muestra 
            ** en el tablero de eventos. 
            */
            axios(`/api/posts/events?init_pagination=false`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  

                var temp = [];
                if(response.data.length > 3){
                    temp = response.data.splice(0,3); //only three elements
                }else{
                    temp = response.data;
                }
                //Formatting elements
                this.events = temp.map(e => {
                    let ret = {
                        id: e.id, 
                        title: e.title,
                        description: e.description,
                        type: e.type,
                        presentation_img: (e.presentation_img != undefined && e.presentation_type != "video") ? window.obj_ac_app.base_url + "/files/images/"+e.presentation_img : null,
                        presentation_type: e.presentation_type,
                        is_popular: e.is_popular,
                        dtl_event: {
                            event_date: e.event_date, //convertir a letras 
                            has_cost: e.has_cost,
                            cost: e.cost,
                            frequency: e.frequency
                        },
                        creator: {
                            id: e.creator_id,
                            nickname: e.nickname
                        }                        
                    }

                    if(ret.presentation_type == "video"){
                        ret.presentation_img = window.obj_ac_app.base_url +"/images/youtube_item.jpg";
                    }
                    return ret;                    
                });
            }).catch(ex=>{
                    let target_process = "Recuperar eventos cercanos";
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
        }
    }
});