Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

const appMemories = new Vue({
    el: "#app_memories_client",
    data: {
        saludo: "Hello Word"
    }
});