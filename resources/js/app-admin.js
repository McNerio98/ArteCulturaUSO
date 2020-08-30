
window.Vue = require('vue');


Vue.component('contact', require('./components/Contacto.vue').default);
Vue.component('pnl-pagination',require('./components/ContainerPagination.vue').default);

const app = new Vue({
    el: '#users',
    data: {
    	users: ['mario','nerio','ceren']
    },
    mounted(){
    	console.log("Me monte en app admin");
    }
});
