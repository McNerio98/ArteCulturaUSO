

const appRoles = new Vue({
    el: "#appRoles",
    data: {
        roles: [],
        caps: [],
        acAppData: window.obj_ac_app,        
        count_caps_global:0,
        role_selected: {caps: []}
    },
    mounted: function(){
        this.loadData();
    },
    methods: {
        has_cap(e){
            return window.has_cap == undefined ? false : window.has_cap(e);
        },              
        loadData: function(){
            axios(`/roles`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }                
                
                this.roles = response.data.roles;
                this.caps = response.data.caps;
                this.count_caps_global = this.caps.length;

                //selecionar la primera 
                if(this.roles.length > 0){
                    this.setRoleSelected(this.roles[0]);
                }
            }).catch(ex=> {
                const target_process = "Obtener roles"; 
                StatusHandler.Exception(target_process,ex);
            });
        },
        setRoleSelected: function(role){
            StatusHandler.ShowLoading("Verificando Rol");
            axios(`/roles/${role.role_id}`).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.CloseLoading();
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                StatusHandler.CloseLoading();
                
                this.role_selected = role;
                this.role_selected.caps = response.data;
            }).catch(ex=>{
                StatusHandler.CloseLoading();
                const target_process = "Obtener configuracion del rol"; 
                StatusHandler.Exception(target_process,ex);
            });
        },
        capsInCurrentRole: function(cap_id){
            return this.role_selected.caps.find(e => e.id == cap_id) != undefined ? true: false;
        },
        switchStateCap: function(event, cap_id){
            event.preventDefault();
            if(this.role_selected.role_id == undefined){
                StatusHandler.ValidationMsg("Ningun rol selecionado");
                return;
            }

            let checked = this.role_selected.caps.find(e => e.id == cap_id) != undefined ? true: false;

            const data = {
                alter_caps: true,
                cap_id : cap_id,
                is_checked: checked
            };

            event.target.disabled = true;//evitar click repetidos  
            axios.put(`/roles/${this.role_selected.role_id}`,data).then(result=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }

                if(data.is_checked){ //si estaba, se quita 
                    let index = this.role_selected.caps.findIndex(e => e.id === response.data.id);
                    if(index === -1){
                        StatusHandler.ValidationMsg("El permiso no existe en la lista actual");
                    }else{
                        this.role_selected.caps.splice(index,1);
                        this.role_selected.count_caps--;
                    }
                }else{ //sino estaba se agrega 
                    this.role_selected.caps.push(response.data);
                    this.role_selected.count_caps++;
                }
            }).catch(ex=>{
                const target_process = "Establecer configuración del rol"; 
                StatusHandler.Exception(target_process,ex);                
            }).finally( res =>{
                event.target.disabled = false;
            });
        }
    }
});