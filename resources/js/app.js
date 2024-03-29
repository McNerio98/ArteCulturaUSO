/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
 import moment from 'moment'
 moment.locale('es-us');
window.Vue = require('vue');

window.Vue.filter('DateFormatES1',function(value){
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY hh:mm a');
    }    
});

//dddd ? 
//	Sunday Monday ... Friday Saturday
window.Vue.filter('DateFormatES2',function(value){
    if (value) {
        return moment(String(value)).format('dddd , DD/MM/YYYY hh:mm a');
    }    
});

window.Vue.filter('DateFormatES3',function(value){
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY');
    }    
});

window.Vue.filter('DateFormatYear',function(value){
    if (value) {
        return moment(String(value)).format('YYYY');
    }    
});

window.Vue.filter('DateFormatDay',function(value){
    if (value) {
        return moment(String(value)).format('DD');
    }    
});

window.Vue.filter('DateFormatDayName',function(value){
    if (value) {
        const str =  moment(String(value)).format('dddd');
        return str.charAt(0).toUpperCase() + str.slice(1);
    }    
});

window.Vue.filter('DateFormatMonthName',function(value){
    if (value) {
        return moment(String(value)).format('MMMM');
    }    
});

window.Vue.filter('DateFormatTime',function(value){
    if (value) {
        return moment(String(value)).format('hh:mm a');
    }    
});


require('./bootstrap');
require('admin-lte');

/**Sweet Alert */
window.Swal = require('sweetalert2');
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

