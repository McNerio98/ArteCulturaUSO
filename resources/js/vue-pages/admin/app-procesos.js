import {proResetEventDates,proTestEmail} from '@/service';
import {isValidEmail} from '@/utils';

const appProcesos = new Vue({
    el: "#appProcesos",
    data: {
        acAppData: window.obj_ac_app
    },
    methods: {
        runResetDatesEvents: async function(){
            const confirm  = await StatusHandler.confirm("¿Esta seguro?","Se restablecerán las fechas de los eventos");
            if(!confirm){return;}

            StatusHandler.ShowLoading("Ejecutando proceso de restablecimiento");
            proResetEventDates().then(result => {
                StatusHandler.CloseLoading();
                const response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }

                const informe = `Completado | Total de registros: ${response.data.total} , Total completados: ${response.data.completed}`;
                StatusHandler.ShowStatus(informe,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.SUCCESS);

            }).catch(ex => {
                StatusHandler.CloseLoading();
                StatusHandler.Exception("Proceso de restablecimiento",ex);
            });         
        },
        runTestEmail: async function(){

            const confirm  = await StatusHandler.confirm("¿Esta seguro?","Se enviara email de prueba");
            if(!confirm){return;}
            const { value: email } = 
            await StatusHandler.inputtext(
                "Ingrese correo electronico",
                "Se enviara email de prueba a este correo",
                "El valor es requerido",
                this.acAppData.current_user.email
            );
            if(!email){return;}
            if(!isValidEmail(email)){
                alert("El email no es valido");
                return;
            }

            const payload = {
                email
            }

            StatusHandler.ShowLoading("Ejecutando proceso de restablecimiento");
            proTestEmail(payload).then(result => {
                StatusHandler.CloseLoading();
                const response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }         
                const informe = `Completado | Envio de correo finalizado`;
                StatusHandler.ShowStatus(informe,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.SUCCESS);       
            }).catch(ex => {
                StatusHandler.CloseLoading();
                StatusHandler.Exception("Proceso de restablecimiento",ex);
            });
        }
    }
});