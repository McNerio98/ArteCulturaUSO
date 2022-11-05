import {proResetEventDates,proTestEmail} from '@/service';
import {isValidEmail} from '@/utils';

const appProcesos = new Vue({
    el: "#appProcesos",
    data: {
        acAppData: window.obj_ac_app
    },
    methods: {
        runResetDatesEvents: async function(){
            const { value: confirm } = await StatusHandler.confirm("¿Esta seguro?","Se restablecerán las fechas de los eventos");
            if(!confirm){return;}

            proResetEventDates().then(result => {
                const response = result.data;
            }).catch(ex => {

            });         
        },
        runTestEmail: async function(){

            const confirm  = await StatusHandler.confirm("¿Esta seguro?","Se enviara email de prueba");
            if(!confirm){return;}
            const { value: email } = await StatusHandler.inputtext("Ingrese correo electronico","Se enviara email de prueba a este correo","El valor es requerido");

            if(!email){return;}
            if(!isValidEmail(email)){
                alert("El email no es valido");
                return;
            }

            proTestEmail().then(result => {
                const response = result.data;
            }).catch(ex => {

            });
        }
    }
});