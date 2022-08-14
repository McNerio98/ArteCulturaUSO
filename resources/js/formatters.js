/**
 * Formateado: JSON
 * Orgien: Tabla medias_profiles
 * Destino: Arbol JSON para Componente Visualizador de medios 
 */
 import moment from 'moment';

 export function formatter86(source,storage_base_url){
    return {
        id: source.id,
        type: 'image',
        name: source.path_file,
        url: storage_base_url + "/files/profiles/" + source.path_file,
        owner: {
            id: source.user_id
        }        
    }
}


/**
 * Formateado: JSON
 * Orgien: Tabla users
 * Destino: Arbol JSON para usuario PostEventCreateComponent
 */

 export function formatter87(user,storage_base_url){
    return {
        id: user.id,
        nickname:user.artistic_name, 
        fullname: user.name,
        profile_path: storage_base_url + "/files/profiles/" + user?.profile_img?.path_file
    }
}


export function getModel88(){
    return {
        id: 0,
        title: '',
        content: '',
        type_post: "event",
        is_popular: false,
        status: 'approved',
        created_at: new Date(),
        event_detail: {
            id: 0,
            event_date: moment().add(2, 'days').toDate(),
            has_cost: false,
            cost: 0,
            frequency: 'unique',
            municipio_id: 0,
            address: "",
            geo_lat: null,
            get_lng: null                    
        },
        owner: {
            id: window.obj_ac_app.current_user.id,
            artistic_name: window.obj_ac_app.current_user.nickname,
            name: window.obj_ac_app.current_user.fullname,
            profile_img: {
                path_file: window.obj_ac_app.current_user.presentation_img.name
            }
        },
        media: []        
    }    
}




 

/**
 * Formateado: JSON
 * Orgien: Tabla post_events con todas sus relaciones 
 * Destino: Arbol JSON para componente muestra PostEventShowComponent y PostEventCreateComponent
 */

 export function formatter88(item,storage_base_url){
    return {
        post: {
            id: item.id,
            title: item.title,
            description: item.content,
            type: item.type_post,
            is_popular: item.is_popular,
            status: item.status,
            created_at: item.created_at,               
        },
        dtl_event: {
            id: item.event_detail?.id,
            event_date: item.event_detail?.event_date,
            has_cost: item.event_detail?.has_cost == 1 ? true: false,
            cost: item.event_detail?.cost,
            frequency: item.event_detail?.frequency,
            is_geo: item.event_detail?.is_geo,
            address: {
                details: item.event_detail?.address,
                depto: 0, //Usar para futuras versiones 
                municipio_id: item.event_detail?.municipio_id
            },
            geo: {
                lat: item.event_detail?.geo_lat,
                lng: item.event_detail?.geo_lat,
            }
        },                        
        creator: {
            id: item.owner.id,
            nickname: item.owner.artistic_name,
            name: item.owner.name,
            profile_img: storage_base_url + "/files/profiles/" + item.owner.profile_img.path_file,    
        },
        media: item.media.map(e=>{
            switch(e.type_file){
                case "image": {e.url = storage_base_url +"/files/images/"  + e.name;break;}
                case "docfile": {e.url = storage_base_url + "/files/docs/pe" + item.id + "/" + e.name;break;}
                case "video": {e.url = storage_base_url + "/images/youtube_item.jpg";break;}
            }
            return e;
        }),
        meta: [],
        mediadrop_ids: []
    }
}

/**
 * Formateado: JSON
 * Orgien: Tabla (memories) con todas sus relaciones  
 * Destino: Arbol JSON para componentes: MemoryCreateComponent and MemoryShowComponent 
 */
export function formatter89(item,storage_base_url){
    var id_presentation = -1;
    if(item.presentation_model != null){
        item.presentation_model.url = storage_base_url +"/files/images/" + item.presentation_model.name; 
        id_presentation = item.presentation_model.id;
    }
    return {
        memory: {
            id: item.id,
            name: item.name,
            other_name: item.other_name,
            type: item.type,
            area: item.area,
            birth_date: item.birth_date,
            birth_dateparse: null,
            death_date: item.death_date,
            content: item.content,
            presentation_img: item.presentation_img,
            creator_id: item.creator_id,
            status: item.creator_id
        },
        presentation_model: item.presentation_model,
        media: item.media.map(e=>{
            e.presentation = (e.id == id_presentation) ? true:false;
            switch(e.type_file){
                case "image": {e.url = storage_base_url +"/files/images/"  + e.name;break;}
                case "docfile": {e.url = storage_base_url + "/files/docs/me" + item.id + "/" + e.name;break;}
                case "video": {e.url = storage_base_url + "/images/youtube_item.jpg";break;}
            }
            return e;            
        }),
        mediadrop_ids: []
    }
}

/**
 * Formateado: JSON
 * Orgien: Tabla (memories) con todas relacion imagen de presentacion 
 * Destino: Arbol JSON para componentes: MemoryMiniComponent
 */

export function formatter90(item,storage_base_url){
    if(item.presentation_model != null){
        item.presentation_model.url = storage_base_url +"/files/images/" + item.presentation_model.name; 
    }    
    return {
        memory: {
            id: item.id,
            name: item.name,
            other_name: item.other_name,
            type: item.type,
            area: item.area,
            birth_date: new Date(item.birth_date),
            death_date: item.death_date != null && item.death_date != "0000-00-00" ? new Date(item.death_date) : null,
            content: item.content,
            presentation_img: item.presentation_img,
            creator_id: item.creator_id,
            status: item.creator_id
        },
        presentation_model: item.presentation_model,
    }
}
