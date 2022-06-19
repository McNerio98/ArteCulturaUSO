/**
 * Formateado: JSON
 * Orgien: Tabla medias_profiles
 * Destino: Arbol JSON para Componente Visualizador de medios 
 */


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
 * Destino: Arbol JSON para usuario Componente PostEvent
 */

 export function formatter87(user,storage_base_url){
    return {
        id: user.id,
        nickname:user.artistic_name, 
        fullname: user.name,
        profile_path: storage_base_url + "/files/profiles/" + user?.profile_img?.path_file
    }
}



/**
 * Formateado: JSON
 * Orgien: Tabla post_events con todas sus relaciones 
 * Destino: Arbol JSON para componente muestra postEvent (PostGeneralComponent)
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
            event_date: item.event_detail?.event_date,
            has_cost: item.event_detail?.has_cost,
            cost: item.event_detail?.cost,
            frequency: item.event_detail?.frequency
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
        meta: [] //no se usa por ahora
    }
}

/**
 * Formateado: JSON
 * Orgien: Tabla (memories) con todas sus relaciones  
 * Destino: Arbol JSON para componentes: MemoryCreateComponent and MemoryPreviewComponent 
 */
export function formatter89(item,storage_base_url){
    return {
        memory: {
            id: item.id,
            name: item.name,
            other_name: item.other_name,
            type: item.type,
            area: item.area,
            birth_date: new Date(item.birth_date),
            death_date: item.death_date != null ? new Date(item.death_date) : null,
            content: item.content,
            presentation_img: item.presentation_img,
            creator_id: item.creator_id,
            status: item.creator_id
        },
        media: item.media.map(e=>{
            switch(e.type_file){
                case "image": {e.url = storage_base_url +"/files/images/"  + e.name;break;}
                case "docfile": {e.url = storage_base_url + "/files/docs/me" + item.id + "/" + e.name;break;}
                case "video": {e.url = storage_base_url + "/images/youtube_item.jpg";break;}
            }
            return e;            
        })
    }
}

/**
 * Formateado: JSON
 * Orgien: Tabla (memories) con todas relacion imagen de presentacion 
 * Destino: Arbol JSON para componentes: MemoryMiniComponent
 */

export function formatter90(item,storage_base_url){
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
        }        
    }
}
