<template>
    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
        <div class="card bg-light">
            <div class="card-header text-muted border-bottom-0">
                Rol | {{contact.role}}
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-7">
                        <h2 class="lead "><b>{{contact.nombre_artistico}}</b></h2>
                        <p class="text-muted text-sm"><b>Rubro Artistico: </b>
                            <template v-for="(e,index) in contact.rubros">
                                <span v-bind:key="index">{{e}}</span> <template v-if="index < (contact.rubros.length - 1)"> / </template>
                            </template>
                        </p>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fas fa-user-shield"></i></span> Propietario: {{contact.propietario}}</li>
                            <li class="small"><span class="fa-li"><i class="fas fa-user-shield"></i></span> Email: {{contact.email}}</li>                            
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Contacto #: {{contact.contacto}}</li>
                        </ul>
                    </div>
                    <div class="col-5 text-center">
                        <img :src="contact.img_profile" alt="" class="img-circle img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-right">                   
                    <a v-if="has_cap('configurar-usuarios') && !contact.isActive && !contact.isRequest" 
                    v-on:click.prevent="changeStatus($event,'enable-user')" class="btn btn-sm bg-success" href="#">
                        <i class="fas fa-unlock"></i> Habilitar
                    </a>                    
                    <a v-if="contact.isActive && has_cap('configurar-usuarios')" href="#" 
                    v-on:click.prevent="changeStatus($event,'disable-user')" class="btn btn-sm bg-danger">
                        <i class="fas fa-lock"></i> Desactivar
                    </a>
                    <a v-if="contact.isRequest && has_cap('configurar-usuarios')" class="btn btn-sm bg-success" 
                     :href="'/admin/users/config/'+this.user.id">
                        <i class="fas fa-lock"></i> Aceptar
                    </a>
                    <!--en este se omite la validacion de permisos, porque si esta en esta pantalla es porque si tiene el permiso de ver usuarios-->
                    <a v-if="has_cap('ver-usuarios')" :href="'/admin/users/config/' + this.user.id" class="btn btn-sm btn-primary">
                        <i class="fas fa-user"></i> Ver Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        user: {type: Object,required:true},
    },    
    data(){
        return {
            contact: {
                id_user: this.user.id,
                role: this.user.role,
                nombre_artistico: this.user.name,
                rubros: JSON.parse(this.user.rubros),
                propietario: this.user.name,
                email: this.user.email,
                img_profile: this.user['img_profile'],
                contacto: this.user.telephone,
                isActive: (this.user.status === 'enabled')?true:false,
                isRequest: (this.user.status === 'request')?true:false
            }
        }
    },
    mounted() {
        console.log('Ciclo de vida del componente me renderize soy el contacto.')
    },
    methods: {
        changeStatus(e,new_status){ //mandar todo esto al controlador de configuraciones 
            let params = {
                operation: new_status.trim()
            };

            $(e.target).addClass("disabled");
            axios.put(`/user/updateConfig/${this.user.id}`,params).then((result)=>{
                let response = result.data;
                console.log("Esta es la response");
                console.log(response);
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };

                if(params.operation == 'enable-user'){
                    this.contact.isActive = true;    
                }

                if(params.operation == 'disable-user'){
                    this.contact.isActive = false;    
                }

            }).catch((ex)=>{
                StatusHandler.Exception("Establecer la descripción del usuario",ex);
            }).finally(()=>{
                $(e.target).removeClass("disabled");
            });
        },
        has_cap(e){
            return window.has_cap(e);
        }
    }
}

</script>
