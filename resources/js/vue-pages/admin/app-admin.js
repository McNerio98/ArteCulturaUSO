
//Se queda 
const STATE_SEARCH= {
    DEFAULT: 1,
    OK: 2,
    VOID: 3
};

import {getRecientes} from '../../service';
import PostEventCard from '../../components/post/PostEventCardComponent.vue';
import {formatter88} from '../../formatters';

const appHome = new Vue({
    el: '#appHome',
    components: {
        'postevent-card' : PostEventCard
    },
    data: {          
        //End key for pagination 
        isLoading: false,
        acAppData: {},
        notifiers_data: {
            posts: -1,
            events: -1,
            requests: -1,
            users: -1
        },
        recientes: []
    },
    mounted: function(){
        this.acAppData = window.obj_ac_app;
        this.notifiers();
        this.getRecientesData();
    },
    methods: {
        /*Method for Pagination */
        changePage(pg){
            this.globalPage = pg;
            this.paginate_approval.current_page = this.globalPage;
            this.approval(pg);
        },        
        /*End Method for Pagination */
        notifiers: function(){
            axios(`/notifiers`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }                
                this.notifiers_data = response.data;
            }).catch(ex =>{
                StatusHandler.Exception("Recuperar PostEvent ", ex);
            });
        },
        getRecientesData: function(){
            this.isLoading = true;
            getRecientes().then(result => {
                let response = result.data;
                if(response.code == 0){
                    this.isLoading = false;
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }     
                this.isLoading = false;
                this.recientes = response.data.map(e => {
                    e.media = [];
                    return formatter88(e, this.acAppData.storage_url);
                });
            }).catch(ex => {
                this.isLoading = false;
                const target_process = "Recuperar elementos"; 
                StatusHandler.Exception(target_process,ex);
            });
        },
        onSelected: function(id){
            window.location.replace(this.acAppData.base_url + `/admin/post/show/${id}`);
        }
    }
});
