
import {getParameters,updateParameter} from '@/service';

const appParams = new Vue({
    el: '#appParams',
    data: {
        isLoadPage: false,
        params: []
    },
    mounted: function(){
        this.loadParameters();
    },
    methods: {
        onCancel: function(index){
            this.params[index].option_value = this.params[index].prev_value;
            this.params[index].edit_mode = false;
        },
        convertForView: function(params){
            const data =  params.map(element => {
                if(element.option_type == 'FLAG'){
                    element.option_value = (element.option_value === 'A');
                }
                if(element.option_type == 'LONGTEXT' || element.option_type == 'TEXT'){
                    element.edit_mode = false;
                }
                element.prev_value = element.option_value;
                return element;
            });

            return data;
        },
        onSave: function(index){
            const temp = Object.assign({}, this.params[index]);
            if(temp.option_type == 'FLAG'){
                temp.option_value = temp.option_value ? 'A' : 'D';
            }

            if(temp.option_value.trim().length == 0){
                alert("El valor es requerido");
                return;
            }

            const payload = {
                option_name: temp.option_name,
                option_type: temp.option_type,
                option_value: temp.option_value
            };

            updateParameter(payload).then(result => {
                const response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    this.params[index].option_value = this.params[index].prev_value;
                }

                this.params[index].prev_value = this.params[index].option_value;
                this.params[index].edit_mode = false;
            }).catch(ex => {
                StatusHandler.Exception("Establecer cambios en parametro del sistema",ex);
            });
        },
        loadParameters: function(){
            this.isLoadPage = true;
            getParameters().then(result => {
                const response = result.data;
                //VALIDAR LA    respuesta


                this.params = this.convertForView(response.data);
            }).catch(ex => {

            });
            this.isLoadPage = false;
        }
    }
});