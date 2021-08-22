
Vue.component('contact', require('../../components/users/Contacto.vue').default);
Vue.component('pnl-pagination',require('../../components/users/ContainerPagination.vue').default);

const appUsers = new Vue({
    el: '#users',
    data: function(){
        return {
            paths: {
                media_profiles: "../files/profiles/",
                files_docs: "../files/pdfs/",
                files_images: "../files/images/",                    
            }            
        }
    }
});