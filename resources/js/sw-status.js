

export default class StatusHandler{
    static get  OPERATION(){
        return {
        INSERT: 1,
        DELETE: 2,
        UPDATE: 3,
        DEFAULT: 4}
    };
    
    static get STATUS(){
        return{
            FAIL: 0,
            SUCCESS: 1
        };
    }

    static confirm(title = "¿Está usted seguro?",text = "¡No podrás revertir esto! "){
        return Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, ¡Continuar!',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false
        }).then((result) => {
            return result.isConfirmed;
        })        
    }

    static inputtext(title,label,textvalid,inputValue = ""){
        return Swal.fire({
            title: title,
            input: 'text',
            inputPlaceholder: label,
            inputValue: inputValue,
            showCancelButton: true,
            allowOutsideClick: false,
            inputValidator: (value) => {
                if (!value) {
                    return textvalid
                }
            }
          })        
    }
    
    static ShowLoading(title = '¡Actualizando datos! Por favor, espere...'){

        let node = "<div id='spinerLoad'><div class='spinner-border' role='status' style='width: 4rem !important; height: 4rem " +
            "!important;'><span class='sr-only'>Loading...</span></div><div class='icon'>" +
            "<span>Cargando ...</span></div></div>";
        Swal.fire({
            title: title,
            html: node,
            showConfirmButton: false,
            allowOutsideClick: false
        });
    }

    static CloseLoading(){
        Swal.close();
    }

    static Exception(target_msg,data_ex){
        //HTTP 401 la petición (request) no ha sido ejecutada porque carece de credenciales
        console.error(data_ex);
        if(data_ex?.response?.status == 401){//para volver al inicio/login 
            window.location.reload();
         }else{
             let msg = "El proceso ("+target_msg+") no se ha podido completar, póngase en contacto con soporte técnico."
             this.ShowStatus(msg,null,StatusHandler.STATUS.FAIL);
        }       
    }

    static ValidationMsg(mensaje){
        Swal.fire({
            icon: 'info',
            title: 'Informe de validación',
            text: mensaje,
            showCloseButton: true
        })        
    }

    static BadDataMsg(mensaje){
        Swal.fire({
            icon: 'error',
            title: 'Inconsistencia de datos',
            text: mensaje,
            showCloseButton: true
        })        
    }    

    static ShowStatus(mensaje, tipo, estado){
        let msgContent = "";
        let msgTitle = "";
        let msgIcon = "";
        msgContent = mensaje;
    
        if (estado == this.STATUS.FAIL) {
            msgIcon = 'error';
            msgTitle = "ERROR EN LA OPERACION";
        } else if (estado == this.STATUS.SUCCESS) {
            msgIcon = "success";
            switch (tipo) {
                case this.OPERATION.INSERT:
                    msgTitle = "Informe de Registro";
                    break;
                case this.OPERATION.UPDATE:
                    msgTitle = "Informe de Actualizacion";
                    break;
                case this.OPERATION.DELETE:
                    msgTitle = "Informe de Eliminacion";
                    break;
                default:
                    msgIcon = "info";
                    msgTitle = "Informe de Operacion";
                    break;
            }
        }
        
        Swal.fire({
            icon: msgIcon,
            title: msgTitle,
            text: msgContent,
            showCloseButton: true
        })
    }

    static get  TOAST_STATUS(){
        return {
        OK: 'success',
        FAIL: 'error',
        INFO: 'info',}
    };

    static StatusToast(type,msg){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: false,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })
          
          Toast.fire({
            icon: type,
            title: msg
          })        
    }
}