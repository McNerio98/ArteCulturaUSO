import axios from "axios";


export function updateTag(tag_id,payload){
    return axios.put(`/tags/${tag_id}`,payload);
}

export function deleteTag(id){
    return axios.delete(`/tag/${id}`);
}

export function upsertCategory(payload){
    return axios.post(`/categories`,payload);
}

export function deleteCategory(id){
    return axios.delete(`/category/${id}`);
}

export function configUser(id,payload){
    return axios.put(`/user/updateConfig/${id}`,payload);
}


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

export function getAdminMemories(filter){
    /**Return Route for Pagination */
    return `/admin/memories/all?filter_letter=${filter}`;
}

export function getAllMemories(filter){
    /**Return Route for Pagination */
    return `/memories/all?filter_letter=${filter}`;
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
    return axios.get('/resources/tipos');

    /*return new Promise((resolve,reject) => {
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
    })*/

}

export function upsertResource(data){
    return axios.post(`/resource`,data);
}

//Obtiene todos los recursos 
export function getAllResources(filter){
    /**Return Route for Pagination */
    return `/resources?filter=${filter}`
}

export function getAdminResources(filter){
    /**Return Route for Pagination */
    return `/admin/resources?filter=${filter}`
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

export function getRecientes(){
    return axios.get('/admin/recientes');
}

export function getElementoTablero(params){
    return axios.get(`/tablero`,{params: params});
}

/**--------------- Promociones ---------------*/
export function promociones(){
    return axios.get(`/promociones`);
}

export function getPromo(id){
    return axios.get(`/promocion/${id}`);
}

export function upsertPromo(payload){
    return axios.post(`/promocion`,payload);
}

export function deletePromo(id){
    return axios.delete(`/promocion/${id}`);
}

/**--------------- Procesos administrativos ---------------*/
export function proResetEventDates(payload){
    return axios.post('/procesofechas',payload);
}

export function proTestEmail(payload){
    return axios.post('/procesoemail',payload);
}


export function getParameters(){
    return axios.get(`/parameters`);
}

export function updateParameter(payload){
    return axios.patch('/parameters',payload);
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



