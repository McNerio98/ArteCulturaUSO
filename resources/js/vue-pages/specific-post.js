

Vue.component('post-component', require('../components/PostComponent.vue').default);
Vue.component('postFormulario-component', require('../components/post/Formulario.vue').default);
Vue.component('postMedia-component', require('../components/post/media.vue').default);



const eventcp = new Vue({
    el: '#event-cp'
});

const postcp = new Vue({
    el: '#post-cp'
});
