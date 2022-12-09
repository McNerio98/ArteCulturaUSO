import Vue from "vue";


const appLogin = new Vue({
    el: "#appLogin",
    data: {
        isSendData: false
    },
    methods: {
        onSubmit: function(){
            this.isSendData = true;
        }
    }
});