import Axios from 'axios';

export function requestAccount(data) {
    return new Promise((resolve, reject) => {
        Axios.post("http://localhost:8000/api/requestaccounts", data).then(response => {
            resolve(response);
        }).catch(e => {
            reject(e);
        })
    })
}

export function getTags() {
    return new Promise((resolve, reject) => {
        Axios.get("http://localhost:8000/api/tags").then(response => {
            resolve(response);
        }).catch(e => {
            reject(e);
        })
    })
}



export function util(type, msg) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });

    Toast.fire({
        icon: type,
        title: msg,
    });
}
