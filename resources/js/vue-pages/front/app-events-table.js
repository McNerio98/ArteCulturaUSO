Vue.component('spinner1',require('@/components/spinners/Spinner1Component.vue').default);

const appEvents = new Vue({
    el: "#appEventsTable",
    data: {
        spinners: {
            S1: false
        },
        events: [],
        postevent_selected: undefined,
        acAppData: window.obj_ac_app
    }, 
    mounted: function(){
        this.loadEvents();
        this.checkParam();
    },
    methods: {
        checkParam: function(){
            var target_item = parseInt($("#targetOpenItem").val());
            target_item =  isNaN(target_item) ? 0 : target_item;
            if(target_item != 0){
                this.loadTarget(target_item);
            }
        },
        onSources: function(){

        },
        onClickEvent: function(event_el){
            this.loadTarget(event_el.id);
        },
        loadEvents: function(page = 1){
            var data = {
                page: page,
                init_pagination: ! this.flags.has_paged1 ? true: false //sino se tiene la paginacion se pide una vez 
            }

            this.spinners.S1 = true;
        }
    }
});