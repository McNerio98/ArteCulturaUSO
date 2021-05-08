
const {getTags} = require("../api/api.service");
Vue.component('post-event', require('../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../components/post/ModalVideo.vue').default);




const appProfile = new Vue({
    el: "#appProfile",
    data: function(){
        return{
            edit_mode_desc: false,
            description_insert: "",
            current_user_id: 0,
            items_post: [],
            items_events: [],

            artistic_name: null,
            count_posts: null,
            count_events: null,
            content_desc: null,
            desc_empty: false,
            isEditStatus: false,

            user: {},
            list_tags: undefined,
            is_edit_tags: false,
            rubro_to_insert: 0,
            rubros: []
        }
    },
    mounted: function(){
        this.loadData();
    },
    created: function(){
        this.current_user_id = $("#current_user_id_request").val();        
    },
    methods: {
        showListTags: function(){
            if(this.list_tags !== undefined){
                this.is_edit_tags = true;
            }else{
                getTags().then((tags)=>{
                    this.list_tags = tags.data;
                    this.is_edit_tags = true;
                }).catch((ex)=>{
                    console.error(ex);
                })
            }
        },
        deleteTagUser: function(id_tag,index){
            console.log("Eliminar con este id " + id_tag);
            axios.delete(`/api/profile/deltag/${this.current_user_id}/${id_tag}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };
                this.rubros.splice(index,1);
            }).catch(ex =>{
                StatusHandler.Exception("Eliminar rubro del usuario",ex);
            });
        },
        addTagUser: function(){
            let params = {
                tag_id: this.rubro_to_insert
            };
            axios.put(`/api/profile/tags/${this.current_user_id}`,params).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }; 
                if(response.code == 409){
                    return; //ya existe 
                }

                this.rubros.push({id: this.rubro_to_insert,name:response.data});            
            }).catch(ex=>{
                StatusHandler.Exception("Guardar el nuevo rubro",ex);
            }).finally(e =>{
                this.is_edit_tags = false;
                this.rubro_to_insert = 0;
            });

        },
        loadPosts: function(){
            const data = {
                type_post: "post",
                user_id: this.current_user_id
            }
            //el componente de la mini general preview aun falta          
        },
        loadEvents: function(){
            const data = {
                type_post: "post",
                user_id: this.current_user_id
            }
            //el componente de la mini general preview aun falta                       
        },
        storeUserDescription: function(){
            let size_campo1 = this.description_insert.length;
            if(size_campo1 < 1  || size_campo1 > 500){
                StatusHandler.ValidationMsg("El tamaño de la descripción no es valida");
                return;
            };

            const meta = {
                user_id: this.current_user_id,//id de usuario logeado 
                meta_key: "user_profile_description",
                meta_value: this.description_insert,
            }; 

            axios.post(`/api/usermeta`,meta).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }; 
                console.log("Este es el resultado")               ;
                console.log(response);
            }).catch((ex)=>{
                StatusHandler.Exception("Registrar el metadato del usuario",ex);
            });
            
        },
        loadData: function(){
            axios(`/api/profile/${this.current_user_id}`).then((result)=>{
                let response = result.data;                
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };

                this.user = response.data.user;
                this.content_desc   = response.data.metas.find(e => e.key === 'user_profile_rawpass')?.value;
                this.rubros = response.data.tags;

            }).catch((ex)=>{
                console.error("UN ERROR",ex);
                closeLoadingAC();
                showAlertMsgAC("Error al recuperar la infomacion",operacion.DEFAULT,operacionStatus.SUCCESS);
            });
        },
        onClickEdit: function(){
            this.edit_mode_desc = true;
        }
    }
});
