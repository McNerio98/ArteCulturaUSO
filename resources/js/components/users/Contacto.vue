<template>
    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
        <div class="card bg-light">
            <div class="card-header text-muted border-bottom-0">
                Rol | {{contact.role}}
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-7">
                        <h2 class="lead"><b>{{contact.nombre_artistico}}</b></h2>
                        <p class="text-muted text-sm"><b>Rubro Artistico: </b>
                            <template v-for="(e,index) in contact.rubros">
                                <span>{{e}}</span> <template v-if="index < (contact.rubros.length - 1)"> / </template>
                            </template>
                        </p>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fas fa-user-shield"></i></span> Propietario: {{contact.propietario}}</li>
                            <li class="small"><span class="fa-li"><i class="fas fa-user-shield"></i></span> Email: {{contact.email}}</li>                            
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Contacto #: {{contact.contacto}}</li>
                        </ul>
                    </div>
                    <div class="col-5 text-center">
                        <img :src="pathImg + contact.img_profile" alt="" class="img-circle img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-right">                   
                    <a v-if="has_cap('activar-usuario') && !contact.isActive && !contact.isRequest" 
                    v-on:click.prevent="changeStatus($event,'enabled')" class="btn btn-sm bg-success" href="#">
                        <i class="fas fa-unlock"></i> Habilitar
                    </a>                    
                    <a v-if="contact.isActive && has_cap('desactivar-usuario')" href="#" 
                    v-on:click.prevent="changeStatus($event,'disabled')" class="btn btn-sm bg-danger">
                        <i class="fas fa-lock"></i> Desactivar
                    </a>
                    <a v-if="contact.isRequest && has_cap('aceptar-usuario')" class="btn btn-sm bg-success" 
                    v-on:click.prevent="changeStatus($event,'enabled')" href="#">
                        <i class="fas fa-lock"></i> Aceptar
                    </a>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-user"></i> Ver Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['user'],    
    data(){
        return {
            pathImg : $('#url_server').val() + "/content/profiles_images",
            contact: {
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
        changeStatus(e,new_status){
            const params = {operation: new_status};
            $(e.target).addClass("disabled");
            axios.put(`/api/users/${this.user.id}`,params).then((result)=>{
                let res = result.data;
                if(res.codeStatus !== 0){
                    this.contact.isActive = (res.operation === 'enabled')?true:false;
                    this.contact.isRequest = (res.operation === 'request')?true:false;
                }else{
                    console.log(res.msg)
                }
            }).catch((ex)=>{
                console.log("Ocurrio un error");
                //sweet aler(ERROR)
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
