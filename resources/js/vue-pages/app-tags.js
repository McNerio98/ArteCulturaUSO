

Vue.component('etiqueta',require('../components/tags/Tag.vue').default);
Vue.component('pnl-tags',require('../components/tags/ContainerTags.vue').default);


const appTags = new Vue({
    el: '#tags'
});