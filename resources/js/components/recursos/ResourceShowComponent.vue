<template>
    <div>
        <div class="row">
            <div class="col">

            </div>
            <div class="col-9">
                <div>
                        <p>Tipo de recurso: {{getTipo}}</p>
                        <h3>{{itemData.resource.name}}</h3>
                        <p>Contiene: 7 Documentos, 2 Imagenes</p>
                </div>
            </div>
        </div>
        <div class="row">
            <p v-html="itemData.resource.description"></p>
        </div>
        <div>
            documentos adjuntos
        </div>
        <!--Preview Component-->
        <div>
            previsualizador
        </div>
    </div>
</template>

<script>
import {getTiposRecursos} from '../../service';
export default {
    props: {
        pdata: {type: Object,required:true}
    },  
    data: function(){
        return {
            tiposRecursos: [],
            acAppData: window.obj_ac_app,
            itemData: JSON.parse(JSON.stringify(this.pdata))            
        }
    },
    created: function(){
        getTiposRecursos().then(result =>{
            this.tiposRecursos = result.data;
        });        
    },
    computed: {
        srcPresentationImg: function(){
            if(this.itemData.presentation_model == null){
                return this.acAppData.base_url + "/images/default_book.jpg";
            }else{
                return this.itemData.presentation_model.url;
            }
        },
        getTipo: function(){
            if(this.tiposRecursos.length > 0){
                return this.tiposRecursos[this.itemData.resource.tipo_id - 1].option;
            }else{
                return "No Especificado";
            }
        }           
    },
    methods: {

    }
    
}
</script>