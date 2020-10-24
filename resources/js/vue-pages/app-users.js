
Vue.component('contact', require('../components/users/Contacto.vue').default);
Vue.component('pnl-pagination',require('../components/users/ContainerPagination.vue').default);


const appUsers = new Vue({
    el: '#users'
});