Vue.component('content-create', require('../../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../../components/post/ModalVideo.vue').default);

Vue.component('pagination-component',require('../../components/pagination/PaginationComponent.vue').default);
Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
// #Estos dos van unidos 
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);


import {formatter88} from '../../formatters';

const appContent = new Vue({
    el: "#appContent",
    data: {
        spinners: {
            S1: false,//load post and events 
        },
        no_data_postevents: false,
        items_postevents: [],
        acAppData: {},
        current_user: {},
        flag_create: {
            type: "post",
            creating: false
        },
        is_mdprofiles: false, // is media profiles 
        media_view: {
            owner: 0,
            target: {},
            items: []
        }        
    },
    mounted: function(){
        this.acAppData = window.obj_ac_app;
        if(this.acAppData.current_user.id != null){
            this.current_user = {
                id                      : this.acAppData.current_user.id,
                nickname        : this.acAppData.current_user.nickname,
                fullname          : this.acAppData.current_user.fullname,
                profile_path    : window.obj_ac_app.base_url + "/files/profiles/" + this.acAppData.current_user.presentation_img.name,
            }
        }

    },
    methods: {
        onItemEdit: function(id){
            //console.log("Editando este id " + id);
            window.location.href = this.acAppData.base_url + '/admin/post/edit/' + id;
        },
        onDeletePost: function(index){
            this.items_postevents.splice(index,1);
        },
        itemLoaded: function(fulldata){
            this.no_data_postevents = (fulldata.length == 0) ? true:false;
            this.items_postevents = fulldata.map(e=>{
                return formatter88(e,this.acAppData.storage_url);
            });
        },
        onSources: function(sources){
            //Formateando segun el formato esperado por el preview 
            var aux = sources.map((e)=>{
                return {
                    id: e.id,
                    type: e.type_file,
                    name: e.name,
                    url: e.url,
                    owner: {
                        id: e.owner
                    }
                }
            });
            this.media_view.items = aux;
            this.media_view.target = aux[0];
            $('#modaPreviewMedia').modal('show');         
        },        
        PostEventCreated: function(e){
            this.items_postevents.unshift(formatter88(e,this.acAppData.storage_url));            
        }
    }
});