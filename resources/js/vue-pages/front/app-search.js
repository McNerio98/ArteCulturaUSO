
//HERE COMPONENTS 

Vue.component('profile', require('../../components/users/SearchProfile.vue').default);

const appTags = new Vue({
    el: '#app-search',
    data: {
        loading_page: true,
        pattern: null,
        profiles: []
    },
    created: function(){
        this.pattern = window.params_search;
    },
    mounted: function(){
        console.log("Componente montado | busqueda");
        this.ByCategory();
    },
    methods: {
        ByCategory: function(){
            console.log("Realizando peticion");
            axios(`/api/search/byCategory/${this.pattern.cat_id}`).then(result=>{
                let response = result.data;
                this.profiles = response.data;
                this.loading_page = false;
            });
        }
    }
});

