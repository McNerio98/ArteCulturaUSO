
Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

Vue.component('memory-create',require('../../components/memories/MemoryCreateComponent').default);
Vue.component('memory-summary',require('../../components/memories/MemoryMiniViewComponent').default);
Vue.component('memory-item',require('../../components/memories/MemoryPreviewComponent.vue').default);

Vue.component('control-trim', require('../../components/trim/TrimComponentv2.vue').default);

//Para el administrador se maneja una sola vista, para homenajes y biografias 
const appMemoriesVue = new Vue({
    el: "#appMemories", 
    data: {
        acAppData: {},
        trim_buffer: {
            aspec_ratio: 2/3,
            file: {},
            window_open: false,
            target: "" //element that open cropper 
        },
        main_img_buffer: {
            change: false,
            base64:  ""
        },
        text: ""
    },
    methods: {
        openTrimPrincipalPic: function(file){
            this.trim_buffer.file  = file;
            this.trim_buffer.window_open = true;
            this.trim_buffer.target = "MAIN_IMG_MEMOY";
        },
        principalPicCropped: function(base64){
            switch(this.trim_buffer.target){
                case "MAIN_IMG_MEMOY": {
                    //pass to component 
                    this.main_img_buffer.change = false;
                    this.main_img_buffer.base64 = base64;
                    this.main_img_buffer.change = true;
                    break;
                }
            }
            //pass chioldren component 
            this.trim_buffer.target = "";
        }
    }
});