/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
 import moment from 'moment'
window.Vue = require('vue');
window.Vue.filter('DateFormatES1',function(value){
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY hh:mm a');
    }    
});


require('./bootstrap');
require('admin-lte');

/**Sweet Alert */
const swal = require('sweetalert2');
window.Swal = swal;
/*This file handle the alerts*/
window.StatusHandler = require('./sw-status').default;




const { values } = require('lodash');




/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('post-component', require('./components/PostComponent.vue').default);
// Vue.component('postFormulario-component', require('./components/post/Formulario.vue').default);
// Vue.component('postMedia-component', require('./components/post/media.vue').default);
// Vue.component('request-component', require('./components/requestAccount.vue').default);

