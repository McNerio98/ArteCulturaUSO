<template>
    <div>
        <div class="srhWrappen">
            <div class="input-group">
                <input type="search" v-model="target_match" v-on:keyup="onKeySearch" class="form-control form-control-lg" placeholder="Ejem. Grupo de música, payasos, casa de la cultura, etc.">
                <div class="input-group-append">
                    <button class="btn btn-lg btn-default" @click="onClickSearchBtn">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="autocomplete-items">
                 
                <div v-for="e in matchs" v-bind:key="e.id_filter" @click="setectMatch(e)"><b>{{e.label.substr(0, target_match.length)}}</b>{{e.label.substr(target_match.length)}}</div>
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

    .autocomplete-items div:hover {
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
        computed: {

        },
        methods: {
            onKeySearch: function(){
                 var list = [];
                if(this.target_match.trim().length > 0 && !this.selected){
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
                this.matchs = list;
            },
            onClickSearchBtn: function(){
                if(this.target_match.trim().length <= 2){
                    //debe ingresar al menos dos caracteres
                    return;
                }

                var temp = {
                    id_filter: 0,
                    label: this.target_match,
                    type_search: undefined
                };
                
                if(this.selected_val.label){
                    if(this.selected_val.label.trim().toUpperCase() == this.target_match.trim().toUpperCase()){
                        temp.id_filter = this.selected_val.id_filter;
                        temp.type_serch = this.selected_val.type_serch;
                    }
                }

                this.$emit("generated-filter", temp);
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