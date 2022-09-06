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

export function getAllMemories(){
    return new Promise((resolve,reject) =>{
        axios.get(`/memories/all`).then(response => {
            resolve(response);
        }).catch(ex => {
            reject(ex);
        });
    });
}

export function deleteMemory($id){
    return axios.delete(`/memories/${$id}`);
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
 * Description: Para futuras versiones, guardar estos valores en tablas de base de datos con los mismo Id que aqui se especifican 
 * @returns {Object} Response 
 */
export function getTiposRecursos(){
    return new Promise((resolve,reject) => {
        resolve({
            code: 1,
            msg: "Datos recuperados",
            data: [
                {id: 1, option: "Libro"},
                {id: 2, option: "Tesis"},
                {id: 3, option: "Informe"},
                {id: 4, option: "Obra"},
                {id: 5, option: "Otros"},
            ]
        });
    })
}

export function upsertResource(data){
    return axios.post(`/resource`,data);
}

//Obtiene todos los recursos 
export function getAllResources(){
    return axios.get(`/resources`);
}

//Obtiene un recurso especifico 
export function getResource(id){
    return axios.get(`/resource/${id}`);
}

export function deleteResource($id){
    return axios.delete('/resource/'+$id);
}

export function uploadImgProfile($data){
    return axios.post('/user/uploadprofileimg',$data);
}

export function deleteImgProfile($id){
    return axios.delete(`/user/deleteprofileimg/${$id}`);
}

export function changeImgProfile($id){
    return axios.put(`/user/selectimgperfil/${$id}`);
}

/**
 * Using Geodecoding API 
 */
export function getGeo(address){
    const ADDR = encodeURIComponent(address);
    return new Promise((resolve,reject) =>{
        axios.get(`/post/geoquery?direction_search=${ADDR}`).then(result =>{
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
    const ADDR = encodeURIComponent(address);
    return new Promise((resolve,reject) =>{
        axios.get(`/post/placesquery?place_search=${ADDR}`).then(result =>{
            resolve(result);
        }).catch(ex =>{
            reject(ex);
        });
    });    
}


export function getNearbyPostEvents(){
    return axios.get('/post/nearby');
}



