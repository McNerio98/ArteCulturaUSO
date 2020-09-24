const { values } = require('lodash');


window.Vue = require('vue');





Vue.component('contact', require('./components/Contacto.vue').default);
Vue.component('pnl-pagination',require('./components/ContainerPagination.vue').default);
Vue.component('etiqueta',require('./components/tags/Tag.vue').default);
Vue.component('pnl-tags',require('./components/tags/ContainerTags.vue').default);

const app = new Vue({
    el: '#users',
    data: {
    	users: ['mario','nerio','ceren']
    },
    mounted(){
    	console.log("Me monte en app admin");
    }
});


// Cree otra 
const app2 = new Vue({
    el: '#tags',

    mounted(){
    	console.log("Me monte en app de tags");
    }
});
