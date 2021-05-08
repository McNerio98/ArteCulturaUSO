import StatusHandler from "../sw-status";


const appConfigUser = new Vue({
    el: '#appConfigUser',
    data: function(){
        return {
            id_current_user: 0,
            loading_page: true,
            is_accepted_request: false,
            is_edit_credential: false,
            is_accept_account: false,
            is_edit_pass: false,
            is_edit_desc: false,
            credentials_restore: {
                username: "",
                email: "",
                pass: "",
                role: null                
            },
            credentials: {
                username: "",
                email: "",
                pass: "",
                role: null
            },
            user: {},
            user_role: {},
            role_selected: '',
            user_description: "",
            username_exist: false,
            email_exist: false,
            send_credentials: false
        }
    },
    created: function(){
        this.id_current_user = isNaN(parseInt($("#current_user_id_request").val()))?0:parseInt($("#current_user_id_request").val());
    },
    mounted: function(){
        this.load_data();
    },
    methods: {
        load_data: function(){
            //console.log("Enviando peticion con este token " + window.axios.defaults.headers.common['Authorization']);
            axios(`/api/users/dataConfig/${this.id_current_user}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }; 
                this.credentials.username = response.data.user.username;
                this.credentials.email = response.data.user.email;
                this.credentials.pass = response.data.metas.find(e => e.key === 'user_profile_rawpass')?.value;
                
                this.user_description = response.data.metas.find(e => e.key === 'user_profile_description')?.value;
                this.user = response.data.user;
                this.user_role = response.data.rol;
                this.role_selected = this.user_role?.id;
                this.credentials.role = this.user_role?.id;
                this.is_accepted_request = response.data.user.status == 'request'?false:true;
            }).catch(ex =>{
                StatusHandler.Exception("Recuperar información del usuario",ex);
            }).finally(()=>{
                this.loading_page = false;
            });
        },
        editCredentials: function(bool){
            if(bool){
                this.is_edit_credential = true;
                this.credentials_restore.username = this.credentials.username;
                this.credentials_restore.email = this.credentials.email;
                this.credentials_restore.pass = this.credentials.pass;
                this.credentials_restore.role  = this.role_selected;
            }else{
                this.is_edit_credential = false;
                this.credentials.username = this.credentials_restore.username;
                this.credentials.email = this.credentials_restore.email;
                this.credentials.pass = this.credentials_restore.pass;
                this.role_selected = this.credentials_restore.role;                
                this.email_exist = false;
                this.username_exist = false;
                this.is_accept_account = false;
            }
        },
        saveDescription: function(){
            if(this.user_description.length < 2){
                StatusHandler.ValidationMsg('La descripción no es valida');
                return;
            }

            let data = {
                operation: 'edit-meta',
                conf_key: 'user_profile_description',
                conf_value: this.user_description
            };

            axios.put(`/api/user/updateConfig/${this.id_current_user}`,data).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };
                
                this.is_edit_desc = false;
            }).catch(ex=>{
                StatusHandler.Exception("Establecer la descripción del usuario",ex);
            });
        },
        deleteAccount: function(){
            console.log("Eliminando cuenta");
        },
        changeStatus: function(e,status){
            let params = {
                operation: status.trim()
            };
            $(e.target).addClass("disabled");            
            axios.put(`/api/user/updateConfig/${this.user.id}`,params).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };

                if(params.operation == 'enable-user'){
                    this.user.status = 'enabled';    
                }

                if(params.operation == 'disable-user'){
                    this.user.status = 'disabled';
                }

            }).catch((ex)=>{
                StatusHandler.Exception("Establecer la descripción del usuario",ex);
            }).finally(()=>{
                $(e.target).removeClass("disabled");
            });            
        },
        updateCredentials: async function(){
            if(this.$refs.frmUpdateCredentials.checkValidity() !== false){
                let response = await this.validateEmailUsername();
                if(response.exists || response.exists == null){
                    return;
                }
                if(this.email_exist || this.username_exist){
                    return;
                }
                
                let params = {
                    operation: 'update-credentials',
                    email: this.credentials.email,
                    username: this.credentials.username,
                    raw_pass: this.credentials.pass,
                    role: this.credentials.role,
                    send_email: false
                }
                
                if(this.is_accept_account){
                    params.status = 'enabled';
                }
                
                this.send_credentials = true;
                axios.put(`/api/user/updateConfig/${this.id_current_user}`,params).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        this.editCredentials(false);
                        return;
                    };
                    if(this.is_accept_account && params.status.length > 0){
                        this.is_accepted_request = true;
                        this.user.status = 'enabled';
                    }                    
                    this.is_accept_account = false;
                }).catch(ex=>{
                    this.editCredentials(false);
                    StatusHandler.Exception("Establecer las nuevas credenciales",ex);
                }).finally(e =>{
                    this.is_edit_credential = false;
                    this.send_credentials = false;
                    this.is_accept_account = false;
                });

            }else{
                this.$refs.frmUpdateCredentials.classList.add('was-validated');
            }            
        },
        validateEmailUsername: function(){
            return new Promise((resolve,reject)=>{
                axios.get(`/api/user/existEmail/${this.id_current_user}/${this.credentials.email}`).then(result=>{
                    let response = result.data;
                    if(response.code == 1 && response.data == 1){
                        this.email_exist = true;
                        resolve({exists:true});
                    }else{
                        this.email_exist = false;
                    }
                    return axios.get(`/api/user/existUsername/${this.id_current_user}/${this.credentials.username}`);
                }).then(result=>{
                    let response = result.data;
                    if(response.code == 1 && response.data == 1){
                        this.username_exist = true;
                        resolve({exists:true});
                    }else{
                        this.username_exist = false;
                        resolve({exists:false});
                    }
                }).catch(ex =>{
                    console.error(ex);
                    reject({exists: null});
                });                
            });
        }
    }
});