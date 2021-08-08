// Este se va para eventos 
Vue.component('post-preview-mini-desing',require('../../components/post/PostEventPreviewRow.vue').default);


const appPopulars = new Vue({
    el: "#appPopulars",
    data: {
        items_postevents: []
    }
});