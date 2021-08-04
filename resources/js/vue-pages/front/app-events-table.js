Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('summary-item',require('../../components/post/PostEventComponent.vue').default);

const appEvents = new Vue({
    el: "#app_events",
    data: {
        flags: {
            has_paged1: false,//SI SE TIENE LA PAGINACION 1, (EVENTOS)
        },
        spinners: {
            S1: false
        },
        events: []
    }, 
    mounted: function(){
        this.loadEvents();
    },
    methods: {
        loadEvents: function(page = 1){
            var data = {
                page: page,
                init_pagination: ! this.flags.has_paged1 ? true: false //sino se tiene la paginacion se pide una vez 
            }


            this.spinners.S1 = true;
            axios(`/api/posts/events`,{params: data}).then(result =>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  

                //Formating 
                this.events = response.data.map(e => {
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
                //Se establece que ya se paginado, si se envio init_pagination = true
                if(! this.flags.has_paged1){this.flags.has_paged1 = true}
            }).catch(ex=>{
                let target_process = "Recuperar eventos cercanos";
                StatusHandler.Exception(target_process,ex);                
            }).finally(e=>{
                this.spinners.S1 = false;
            })
        }
    }
});