window.Vue = require('vue');
window.jquery = require('jquery')
Vue.component('request-component', require('../components/requestAccount.vue').default);

const appPost = new Vue({
    el: '#request'
});
