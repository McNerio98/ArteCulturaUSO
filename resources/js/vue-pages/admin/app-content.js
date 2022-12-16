
Vue.component('pagination-component',require('@/components/pagination/PaginationComponent.vue').default);
Vue.component('media-viewer', require('@/components/media/ViewMediaComponent.vue').default);
Vue.component('preview-media',require('@/components/media/PreviewMediaComponent.vue').default);


import {formatter88,getModel88,formatter87} from '@/formatters';
import PostEventCreate from '@/components/post/PostEventCreateComponent.vue';
import PostEventShowComponent from '@/components/post/PostEventShowComponent.vue';

const appContent = new Vue({
    el: "#appContent",
    components:{
        "postevent-create": PostEventCreate,
        "postevent-show": PostEventShowComponent
    },
    data: {
        modelo_create: null,
        isCreating: false,
        create_type: "post",
        spinners: {
            S1: false,//load post and events 
        },

        no_data_postevents: false,
        items_postevents: [],
        acAppData: window.obj_ac_app,
        is_mdprofiles: false, // is media profiles 
        media_view: {
            owner: 0,
            target: {},
            items: []
        }        
    },
    methods: {
        onCreate: function(tipo){
            //Este flag viene de la version anterior
            //Version, crear post o evento
            this.isCreating= true;
            const valid = ['post','event'];
            if(!valid.includes(tipo)){
                alert("Inconsistencia de datos");
                return;
            }
            const nuevo = getModel88();
            nuevo.type_post = tipo;
            this.modelo_create = formatter88(nuevo,this.acAppData.storage_url);
        },
        onUpdatePostEvent: function(id){
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

            if(fulldata.length != 0){
                this.onCreate('event');
            }
        },
        onSources: function(sources){
            var  items = sources.map((e)=>{{
                return formatter87(e,0);
            }});

            this.$refs.mediaviewer.builderAndShow(items,'POST_EVENTS',items[0]);            
        },        
        PostEventCreated: function(e){
            this.items_postevents.unshift(formatter88(e,this.acAppData.storage_url));  
            this.onCreate('event');
        },
        onPromo: function(id){
            window.location.replace(this.acAppData.base_url + `/admin/promociones/create?tarid=${id}&tartype=postevent`);
        }
    }
});