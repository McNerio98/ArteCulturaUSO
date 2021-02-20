const { result } = require('lodash');
const { default: StatusHandler } = require('../sw-status');

Vue.component('modal-trim-img', require('../components/trim/TrimComponent.vue').default);
Vue.component('tag-rubro',require('../components/tags/Tag.vue').default);

const appRubros = new Vue({
    el: '#appRubros',
    data: {
        categories: [],
        cat_selected: {id: 0, img_presentation: "/images/default_img_category.png",name: "",tags: []},
        ref_cat_selected: {id: 0, img_presentation: "",name: ""},
        cat_id_selected: 0,
        tags: [],
        pnl_wait1: false,
        modal_cropper: "DEFAULT",
        category_insert: "",
        tag_insert: "",
        creating_category: false,
        creating_tag: false
    },
    methods: {
        loadCategories: function(){
            axios(`/api/categories`).then((result)=>{
                let response = result.data;
                this.categories = response.data;
                this.selectFirstCategory();
            }).catch((ex)=>{
                console.error("Error");
                console.log(ex);
            })
        },
        selectFirstCategory: function(){
            if(this.categories.length > 0){
               this. selectCategory(this.categories[0]);
            }
        },
        selectCategory: function(cat){
            this.ref_cat_selected = cat;
            this.loadDataCategory(cat.id);
        },
        loadDataCategory: function(id_category){
            axios(`/api/tags/byCategory/${id_category}`).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                   this.ref_cat_selected = {id: 0, img_presentation: "",name: ""}; 
                    return;
                }
                this.tags = response.data;            
            }).catch((ex)=>{
                this.ref_cat_selected = {id: 0, img_presentation: "",name: ""}; 
                StatusHandler.Exception("Recuperar tags de categoria",ex);
            });
        },
        storeCategory: function(){
            let size_campo1 = this.category_insert.length;
            if(size_campo1 < 1  || size_campo1 > 50){
                StatusHandler.ValidationMsg("Nombre de Categoria no valido");
                return;
            }
            const data = {
                category_name: this.category_insert.trim()
            };
            axios.post(`/api/categories`,data).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };
                //ingresando la nueva categoria 
                this.categories.unshift(response.data);
                this.creating_category = false;
                this.category_insert = "";
                this. selectCategory(this.categories[0]);
            }).catch((ex)=>{
                StatusHandler.Exception("Registrar Nueva Categoria",ex);
            });
        },
        storeTag: function(){
            let size_campo1 = this.tag_insert.length;
            if(size_campo1 < 1  || size_campo1 > 50){
                StatusHandler.ValidationMsg("Nombre de etiqueta no valido");
                return;
            }
            const data = {
                tag_name: this.tag_insert,
                category_id: this.ref_cat_selected.id
            };
            axios.post(`/api/tags`,data).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };
                this.tags.unshift(response.data);
                this.tag_insert = "";
                this.creating_tag = false;
            }).catch((ex)=>{
                StatusHandler.Exception("Registrar Nueva Etiqueta/Rubro",ex);
            });
        },  
        deleteTag: function(index){
            this.tags.splice(index,1);
        },
        openModalTrim: function(){
            this.modal_cropper = "IMG_PRESENTATION";
            $("#hiddenImgFileTrim").trigger("click");
        },
        filterModalCropper: function(base64){
            if(this.modal_cropper === "DEFAULT"){console.error("Llamada inconsiste de modal cropper");return;};

            switch(this.modal_cropper){
                case "IMG_PRESENTATION": {
                    this.SendImgPresentation(base64);
                }
            }
        },
        SendImgPresentation: function(base64){
            //Validaciones
            //Enviar peticion 

            //si se confirma
            this.pnl_wait1=true; 
            let prev_path_img = this.ref_cat_selected.img_presentation;

            let data = {
                id_category: this.ref_cat_selected.id,
                current_name_img: prev_path_img.trim(),
                img_presentation: base64

            };
            axios.post(`/api/categories/saveImgPresentation`,data).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    this.ref_cat_selected.img_presentation = prev_path_img;
                    return;
                }
                this.ref_cat_selected.img_presentation = response.data; //containt new path 
            }).catch((ex)=>{
                StatusHandler.Exception("Establecer presentación de categoría",ex);
                this.ref_cat_selected.img_presentation = prev_path_img;
            }).finally(()=>{
                this.pnl_wait1 = false;
            });
        }
    },
    mounted: function(){
        this.loadCategories();
    }
});