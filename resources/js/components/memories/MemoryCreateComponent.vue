<template>
    <div class="pnl-memory card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-7">
                    <h5>Talento/rubro</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email">
                    </div>                    
                    <h5>Nombre completo</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email">
                    </div>   
                    <h5>Otros nombres</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email">
                    </div>                       
                    <h5>Fecha nacimiento</h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email">
                    </div>                                           
                    <h5>Fecha de fallecimiento </h5>                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email">
                    </div>  
                    <a class="btn btn-app">
                        <i class="fas fa-save"></i> Guardar
                    </a>                    
                    <a class="btn btn-app">
                        <i class="fas fa-save"></i> Cancelar
                    </a>                       
                </div>
                <div class="col-12 col-md-5">
                    <div class="wrpp-img-presentation">
                       
                        
                    </div>
                    <button class="btn btn-block btn-secondary btn-flat">Subir</button>
                </div>                
            </div>
            <div class="row">
                <div class="col-12">

                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .pnl-memory{
        width: 100%;
        max-width: 800px;
        margin: auto;
    }

    .wrpp-img-presentation{
        background-color: red;
        position: relative;
        width: 100%;
        padding-top: 130%; /* 1:1 Aspect Ratio */
    }    
</style>
<script>
    export default {
        data(){
            return{
                 text: "Hello World!",
                saludo: "Hola"
            }
        } 
    }
</script>
