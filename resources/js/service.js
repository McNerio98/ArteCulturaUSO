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

export function upsertPostEvent(data){
    return new Promise((resolve,reject) =>{
        axios.post(`/postevent`,data).then(response => {
            resolve(response);
        }).catch(ex => {
            reject(ex);
        });
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

export function getAdminMemories(){
    return new Promise((resolve,reject) =>{
        axios.get(`/admin/memories/all`).then(response => {
            resolve(response);
        }).catch(ex => {
            reject(ex);
        });
    });
}


/**
 * Description: Para futuras versiones, guardar estos valores en tablas de base de datos con los mismo Id que aqui se especifican 
 * @returns {Object} Response 
 */
export function getMunicipios(){
    return new Promise((resolve,reject) =>{
        resolve({
            code: 1,
            msg: "Datos recuperados",
            data: [
                {id: 1,municipio: "Acajutla"},
                {id: 2,municipio: "Armenia"},
                {id: 3,municipio: "Caluco"},
                {id: 4,municipio: "Cuisnahuat"},
                {id: 5,municipio: "Izalco"},
                {id: 6,municipio: "Juayúa"},
                {id: 7,municipio: "Nahuizalco"},
                {id: 8,municipio: "Nahulingo"},
                {id: 9,municipio: "Salcoatitán"},
                {id: 10,municipio: "San Antonio del Monte"},
                {id: 11,municipio: "San Julián"},
                {id: 12,municipio: "Santa Catarina Masahuat"},
                {id: 13,municipio: "Santa Isabel Ishuatán"},
                {id: 14,municipio: "Santo Domingo de Guzmán"},
                {id: 15,municipio: "Sonsonate"},
                {id: 16,municipio: "Sonzacate"},
            ]
        })
    })
}

/**
 * Using Geodecoding API 
 */
export function getGeo(address){
    var API_KEY = "AIzaSyAzDI1Ey1kZJHyTgPXymVWc95nP8tYWnOk";
    var ADDR = encodeURIComponent(address);
    var ENDPOINT = `https://maps.googleapis.com/maps/api/geocode/json?address=${ADDR}&key=${API_KEY}`;
    
    return new Promise((resolve,reject) =>{
        axios.get(ENDPOINT).then(result =>{
            resolve(result);
        }).catch(ex =>{
            reject(ex);
        });
    });
}

/**
 * Using Places API 
 */
export function getPlaces(address){
    var API_KEY = "AIzaSyAzDI1Ey1kZJHyTgPXymVWc95nP8tYWnOk";
    var ADDR = encodeURIComponent(address);
    var FIELDS = encodeURIComponent("formatted_address,name,geometry");
    var ENDPOINT = `https://maps.googleapis.com/maps/api/place/findplacefromtext/json?fields=${FIELDS}&input=${ADDR}&inputtype=textquery&key=${API_KEY}`;
    //var ENDPOINT = `https://maps.googleapis.com/maps/api/place/textsearch/json?query=${ADDR}&key=${API_KEY}`;
    return new Promise((resolve,reject) =>{
        axios.get(ENDPOINT).then(result =>{
            resolve(result);
        }).catch(ex =>{
            reject(ex);
        });
    });    
}
