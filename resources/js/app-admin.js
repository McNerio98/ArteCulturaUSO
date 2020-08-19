
window.Vue = require('vue');


Vue.component('contact', require('./components/Contacto.vue').default);

const app = new Vue({
    el: '#users',
});
