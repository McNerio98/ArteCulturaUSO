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

export function getPostEvent(id){
    return new Promise((resolve,reject)=>{
        axios.get(`/postevent/${id}`).then(response => {
            resolve(response);
        }).catch(ex => {
            reject(ex);
        })
    });
}

export function upsertMemory(data){
    return new Promise((resolve,reject) =>{
        axios.post(`/memories`,data).then(response => {
            resolve(response);
        }).catch(ex => {
            reject(ex);
        });
    });
}

export function getMemory(id){
    return new Promise((resolve,reject) =>{
        axios.get(`/memories/find/${id}`).then(response => {
            resolve(response);
        }).catch(ex => {
            reject(ex);
        });
    });
}



