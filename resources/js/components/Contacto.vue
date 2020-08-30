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
                    <a href="#" class="btn btn-sm bg-dark">
                        <i class="fas fa-cog"></i>
                    </a>                    
                    <a v-if="!contact.isActive" href="#" class="btn btn-sm bg-danger">
                        <i class="fas fa-unlock"></i> Habilitar
                    </a>                    
                    <a v-if="contact.isActive" href="#" class="btn btn-sm bg-danger">
                        <i class="fas fa-lock"></i> Desactivar
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
                rubros: ['Diseñador','UX','Musico','Pintor'],
                propietario: this.user.name,
                email: this.user.email,
                img_profile: this.user['img-profile'],
                contacto: this.user.telephone,
                isActive: true,
                isRequest: false
            }
        }
    },
    mounted() {
        console.log('Ciclo de vida del componente me renderize soy el contacto.')
    }
}

</script>
