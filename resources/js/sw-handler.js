
export const operacion = {
    INSERT: 1,
    DELETE: 2,
    UPDATE: 3,
    DEFAULT: 4
}



export const operacionStatus = {
    FAIL: 0,
    SUCCESS: 1
}

export function showLoadingAC() {
    let node = "<div id='spinerLoad'><div class='spinner-border' role='status' style='width: 4rem !important; height: 4rem " +
        "!important;'><span class='sr-only'>Loading...</span></div><div class='icon'>" +
        "<span>Cargando ...</span></div></div>";
    Swal.fire({
        html: node,
        showConfirmButton: false
    });
}

function closeLoadingAC() {
    Swal.close();
}

function showAlertMsgAC(mensaje, tipo, estado) {
    let msgContent = "";
    let msgTitle = "";
    let msgIcon = "";
    msgContent = mensaje;

    if (estado == operacionStatus.FAIL) {
        msgIcon = 'error';
        msgTitle = "ERROR EN LA OPERACION";
    } else if (estado == operacionStatus.SUCCESS) {
        msgIcon = "success";
        switch (tipo) {
            case operacion.INSERT:
                msgTitle = "Informe de Registro";
                break;
            case operacion.UPDATE:
                msgTitle = "Informe de Actualizacion";
                break;
            case operacion.DELETE:
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
        showCloseButton: true,
        timer: 2500
    })
}



