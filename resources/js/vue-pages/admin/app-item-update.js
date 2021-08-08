Vue.component('content-create', require('../../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../../components/post/ModalVideo.vue').default);

Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);

const appUpdateItem = new Vue({
    el: "#appUpdateItem",
    data: {
        spinners: {
            S1:false //for loading info post
        },
        target_id: 0,
        buffer: { //buffer para edicion
            edit_mode: false, 
            source: {}
        }
    },
    mounted: function(){
        this.target_id = parseInt(document.getElementById("temp_iden_edit").value);
        
        if(!isNaN(this.target_id)){
            this.loadData();
        }
    },
    methods: {
        loadData: function(){
            this.spinners.S1 = true;
            axios(`/api/post/${this.target_id}`).then(result=>{
                let response = result.data;
                if(response.code == 0){ //sino existe lo detiene aqui 
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  
                
                var e = response.data;
                this.buffer.source =  {
                    post: {
                        id: e.id,
                        title: e.title,
                        description: e.content,
                        type: e.type_post,
                        is_popular: false,
                        status: 'review',
                        created_at: e.created_at,
                    },
                    dtl_event: {
                        event_date: e.event_date,
                        has_cost: e.has_cost,
                        cost: e.cost,
                        frequency: e.frequency
                    },                    
                    creator: {
                        id: e.creator_id,
                        name: e.creator_name,
                        nickname: e.creator_nickname,
                        profile_img:  window.obj_ac_app.base_url + "/files/profiles/" + e.creator_profile, 
                    },
                    media: e.media.map(ng => {//el formato para esto se filtra en el otro compnente
                        switch(ng.type_file){//IMPORTANT FOR EDIT MODE PASS URL FOR ALL ITEMS 
                            case "image": {ng.url = window.obj_ac_app.base_url +"/files/images/"  + ng.name;break;}
                            case "docfile": {ng.url = window.obj_ac_app.base_url + "/files/pdfs/" + ng.name;break;}
                            case "video": {ng.url = window.obj_ac_app.base_url + "/images/youtube_item.jpg";break;}
                        }
                        return ng;
                    })
                }                

                //Dejar esto asi ya que esto ayuda a sincronizar los datos del componente 
                //con los pasados como parametros 
                //Es decirt, se debe colocar una flag en el componente para indicar que se mostrara si esta en modo edicion,
                //pero antes de mostrar la flag se pasan los daots 
                this.buffer.edit_mode = true;

            }).catch(ex=>{
                let target_process = "Recuperar elemento";
                StatusHandler.Exception(target_process,ex);                
            }).finally(e=>{
                this.spinners.S1 = false;
            });
        }
    }

});