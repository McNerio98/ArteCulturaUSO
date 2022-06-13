import axios from "axios";


export function getUserProfileInformation(id){
    return new Promise((resolve,reject)=>{
        axios.get(`/profile/information/${id}`).then(response => {
            resolve(response);
        }).catch(ex => {
            reject(ex);
        })
    });
}

