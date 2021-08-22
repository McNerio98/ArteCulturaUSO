<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Componente de VUEJS</div>

                    <div class="card-body">
                        Este es un componente de VUEJS,
                        puedes encontrarlo en resources/js/components
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Ciclo de vida del componente.')
        }
    }
</script>
