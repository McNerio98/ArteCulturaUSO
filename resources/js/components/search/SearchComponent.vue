<template>
    <div>
        <div class="srhWrappen">
            <div class="input-group">
                <input type="search" 
                @keyup="onKeySearch($event)"
                @keyup.up="onUpItem()"
                @keyup.down="onDownItem()"
                @keyup.esc="onEscape"
                @keyup.enter="onClickSearchBtn" 
                v-model="target_match"
                class="form-control form-control-lg" placeholder="Ejem. Grupo de música, payasos, casa de la cultura, etc.">
                <div class="input-group-append">
                    <button class="btn btn-lg btn-default" @click="onClickSearchBtn">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="autocomplete-items">
                 
                <div v-for="(e,index) in matchs" 
                :class="{active : index_navi == index}"
                v-bind:key="index" 
                @click="setectMatch(e)"><b>{{e.label.substr(0, target_match.length)}}</b>{{e.label.substr(target_match.length)}}</div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .srhWrappen{
        width: 100%;
        max-width: 700px;
        margin: auto;
        position: relative;     
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        /*position the autocomplete items to be the same width as the container:*/
        top: 100%;
        left: 0;
        right: 0;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    .autocomplete-items div:hover, .autocomplete-items div.active{
    /*when hovering an item:*/
        background-color: #e9e9e9;
    }    

</style>
<script>
    export default {
        props: {
            prevSearch: {type: Object, default: function(){
                return {
                    id_filter: undefined,
                    label: undefined,
                    type_search: undefined,
                }
            }}
        },
        data(){
            return {
                //items: ["Payasos", "Musicos"],
                items: [],
                matchs: [],
                target_match: "",
                selected_val: {},
                selected: false,
                max_match: 5,
                index_navi: -1, //indica el elemento en la navegacion (down and up)
            }
        },
        created: function(){
            if(this.prevSearch.label || this.prevSearch.id_filter || this.prevSearch.type_search){
                this.target_match = this.prevSearch.label;
                this.selected = true;
                this.$emit("generated-filter", this.prevSearch);
            }
        },
        mounted: async function(){
            var items1 = await this.getTags();
            var items2 = await this.getCategories();

            if(items1.code == 0 || items2.code == 0){
                StatusHandler.ShowStatus(items1.msg + " / " + items2.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                return;
            }
            this.items = items1.data.concat(items2.data);

            //emitir datos de busqueda, los mismos  
        },
        methods: {
            onDownItem: function(){
                if(this.matchs.length == 0){return;}
                if((this.index_navi + 1) < this.matchs.length){
                    this.index_navi++;
                }

                if(this.index_navi >= 0 && this.index_navi < this.matchs.length){
                    this.target_match = this.matchs[this.index_navi].label;
                }
            },
            onUpItem: function(){
                if(this.matchs.length == 0){return;}
                if((this.index_navi - 1) >= -1){
                    this.index_navi--;
                }
                if(this.index_navi >= 0 && this.index_navi < this.matchs.length){
                    this.target_match = this.matchs[this.index_navi].label;
                }                
            },
            onEscape: function(){

            },
            onClickSearchBtn: function(){

            },
            onKeySearch: function(e){
                 var list = [];
                 //if keyup, keydown or enter skip 
                 if(e.keyCode== 38 || e.keyCode == 40 || e.keyCode == 13){return;}
                if(this.target_match.trim().length > 0){
                    //filtrando
                    var count = 0;
                    for(var i = 0; i < this.items.length; i++){
                        if(this.items[i].label.substr(0,this.target_match.length).toUpperCase() == this.target_match.toUpperCase()){
                            list.push(this.items[i]);
                            count++;
                        }

                        if(count >= this.max_match){
                            break;
                        }
                    }
                }
                //reinicio el desplazamiento 
                this.index_navi = -1;
                //agrego las nuevas coincidencias 
                this.matchs = list;
            },
            onClickSearchBtn: function(){
                if(this.target_match.trim().length <= 2){
                    //debe ingresar al menos dos caracteres
                    return;
                }

                //verificar si hay uno seleccionaro 
                //Se tiene uno selecionado 

                var temp = {
                    id_filter: 0,
                    label: this.target_match,
                    type_search: "custom"
                };

                if(this.index_navi !== -1){
                    temp.id_filter = this.matchs[this.index_navi].id_filter;
                    temp.type_search = this.matchs[this.index_navi].type_search;
                }                
                
                if(this.selected_val.label){
                    if(this.selected_val.label.trim().toUpperCase() == this.target_match.trim().toUpperCase()){
                        temp.id_filter = this.selected_val.id_filter;
                        temp.type_serch = this.selected_val.type_serch;
                    }
                }

                this.$emit("generated-filter", temp);
                this.index_navi = -1;
                this.matchs = [];
            },
            setectMatch: function(temp){
                this.selected = true;
                this.target_match =  temp.label;
                this.selected_val = temp;
                this.matchs = [];
                this.$emit("generated-filter", this.selected_val);
            },
            getTags: function(){
                return new Promise((resolve,reject)=>{
                    axios(`/api/tags`).then(result =>{
                        let response = result.data;
                        if(response.code == 0){resolve({code: 0, msg: "Problema al recuperar tags", data: null});}
                        
                        let temp = response.data.map(e=>{
                            return {
                                id_filter: e.id,
                                label: e.name,
                                type_search: "tag"
                            }
                        });
                        resolve({code: 1, msg: "", data: temp});
                    }).catch(ex =>{
                        resolve({code: 0, msg: "Problema al recuperar tags", data: null});
                    }); 
                });
            },
            getCategories: function(){
                return new Promise((resolve,reject)=>{
                    axios(`/api/categories`).then(result=>{
                        let response = result.data;
                        if(response.code == 0){resolve({code: 0, msg: "Problema al obtener las Categorias", data: null});}
                        let temp = response.data.map(e=>{
                            return {
                                id_filter: e.id,
                                label: e.name,
                                type_search: "cat"
                            }
                        });
                        resolve({code: 1, msg: "", data: temp});                        
                    }).catch(ex=>{
                        resolve({code: 0, msg: "Problema al obtener las Categorias", data: null});
                    });
                });
            }
        }
    }
</script>