import NoDataRegister from '@/components/NoDataRegister.vue';
import PromoCreate from '@/components/promociones/PromoCreateComponent.vue';
import PromoSummary from '@/components/promociones/PromoCardComponent.vue'
import {formatter92,getModel92} from '@/formatters';
import PromoShow from '@/components/promociones/PromoShowComponent.vue';
import {getPromo,promociones} from '@/service';
Vue.component('spinner1',require('@/components/spinners/Spinner1Component.vue').default);

/**---------- index---------- **/
if(document.getElementById("appPromoIndex") != undefined){
    const appPromoIndex = new Vue({
        el: '#appPromoIndex',
        components: {
            'no-records' : NoDataRegister,
            'promo-summary' : PromoSummary
        },
        data: {
            acAppData: window.obj_ac_app,
            items: [],
            isGettingResources: false
        },
        created: function(){
            this.getDataPromotions();
        },
        methods: {
            getDataPromotions: function(){
                this.isGettingResources = true;
                promociones().then(result => {
                    const response = result.data;
                    if(response.code == 0){
                        this.isGettingResources = false;
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }  

                    this.isGettingResources = false;
                    this.items = response.data.map(e => {
                        return formatter92(e,this.acAppData.storage_url)
                    })

                }).catch(ex => {
                    this.isGettingResources = false;
                    const target_process = "Recuperar elementos"; 
                    StatusHandler.Exception(target_process,ex);
                });
            },
            onRead: function(id){
                window.location.replace(this.acAppData.base_url + "/admin/promociones/show/"+id);
            },
            onPreview: function(){

            }
        }
    });
}


/**---------- create, edit---------- **/
if(document.getElementById("appPromoCreateUpdate") != undefined){
    const appPromoCreateUpdate = new Vue({
        el: '#appPromoCreateUpdate',
        components: {
            'promo-create' : PromoCreate
        },
        data: {
            idpromo: 0,
            targetId: 0,
            targetType: "",
            modelo: [],
            acAppData: window.obj_ac_app,
        },
        mounted: function(){
            this.idpromo = isNaN(parseInt($("#idpromo").val())) ? 0 : parseInt($("#idpromo").val());
            if(this.idpromo === 0){
                this.createPromo();
            }else{
                this.getDataPromo();
            }
        },
        methods: {
            createPromo: function(){
                this.modelo.push(formatter92(getModel92(),this.acAppData.storage_url));
                const targetId = isNaN(parseInt($("#tarid").val())) ? 0 : parseInt($("#tarid").val());
                const targetType = $("#tartype").val();                
                if(targetId != 0){
                    this.configurarParam(targetId,targetType);
                }
            },
            configurarParam: function(targetId,targetType){
                let type_ads = 0;
                switch(targetType){
                    case "postevent": {
                        type_ads = 1;
                        break;
                    }
                    case "memory": {
                        type_ads = 2;
                        break;
                    }                       
                    case "resource": {
                        type_ads = 3;
                        break;
                    }
                    case "profile": {
                        type_ads = 4;
                        break;
                    }
                }
                if(type_ads != 0){
                    this.modelo[0].promo.type_ads = type_ads;
                    this.modelo[0].promo.item_id = targetId;
                }
            },
            onEditPromo: function(id){
                window.location.href = this.acAppData.base_url + "/admin/promociones/create?idp="+id;
            },
            getDataPromo: function(){
                getPromo(this.idpromo).then(result => {
                    const response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }

                    this.modelo.push(formatter92(response.data,this.acAppData.storage_url));

                }).catch(ex => {
                    let target_process = "Recuperar elemento especificado"; 
                    StatusHandler.Exception(target_process,ex);
                });
            },
            onCreatePromo: function(id){
                //On Create or Edit Promo
                window.location.replace(this.acAppData.base_url + "/admin/promociones/show/"+id);
            }
        }
    });
}


/**---------- show---------- **/
if(document.getElementById("appPromoShow") != undefined){
    const appPromoShow = new Vue({
        el: '#appPromoShow',
        components: {
            'promocion': PromoShow
        },
        data: {
            idpromo:0,
            modelo: [],
            acAppData: window.obj_ac_app
        },
        created: function(){
            this.idpromo = isNaN(parseInt($("#idpromo").val())) ? 0 : parseInt($("#idpromo").val());
        },
        mounted: function(){
            this.getData();
        },
        methods: {
            getData: function(){
                if(this.idpromo == 0){
                    window.location.replace(this.acAppData.base_url  + "/admin/promociones");
                    return;
                }     
                
                getPromo(this.idpromo).then(result => {
                    const response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }

                    this.modelo.push(formatter92(response.data,this.acAppData.storage_url));

                }).catch(ex => {
                    let target_process = "Recuperar elemento especificado"; 
                    StatusHandler.Exception(target_process,ex);
                });
            },
            onEditPromo: function(id){
                window.location.replace(this.acAppData.base_url + "/admin/promociones/create?idp="+id);
            },
            onDeletedPromo: function(id){
                window.location.replace(this.acAppData.base_url + "/admin/promociones");
            }
        }
    });
}