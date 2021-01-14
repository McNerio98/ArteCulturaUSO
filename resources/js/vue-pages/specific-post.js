
Vue.component('post-event', require('../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../components/post/ModalVideo.vue').default);


const eventcp = new Vue({
    el: '#event-cp'
});

const postcp = new Vue({
    el: '#post-cp'
});
