<template>
    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
        <div class="card bg-light">
            <div class="card-header text-muted border-bottom-0">
                Rol | Super Admin
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-7">
                        <h2 class="lead"><b>{{contact.nombre_artistico}}</b></h2>
                        <p class="text-muted text-sm"><b>Rubro Artistico: </b> Web Designer / UX / Graphic Artist / Coffee Lover </p>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fas fa-user-shield"></i></span> Propietario: {{contact.propietario}}</li>
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Contacto #: + 800 - 12 12 23 52</li>
                        </ul>
                    </div>
                    <div class="col-5 text-center">
                        <!--
                        <img src="../../dist/img/user1-128x128.jpg" alt="" class="img-circle img-fluid">
                    -->
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-right">
                    <a href="#" class="btn btn-sm bg-dark">
                        <i class="fas fa-cog"></i>
                    </a>                    
                    <a v-if="!contact.isActive" href="#" class="btn btn-sm bg-danger">
                        <i class="fas fa-unlock"></i>Habilitar
                    </a>                    
                    <a v-if="contact.isActive" href="#" class="btn btn-sm bg-danger">
                        <i class="fas fa-lock"></i>Desactivar
                    </a>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fas fa-user"></i>Ver Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            contact: {
                nombre_artistico: 'Banda Sonora',
                propietario: 'Juan Carlos',
                isActive: true
            }
        }
    },
    mounted() {
        console.log('Ciclo de vida del componente.')
    }
}

</script>
