export function municipiosItems(){
    return [
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
        {id: 16,municipio: "Sonzacate"}        
    ]
}

export function getABC(){
    return ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']
}

export function directionsTokens(direction){
    var item = direction.replace(/(c)(ll|all|l)(\.| )/gi,"calle ");
    item = item.replace(/av(\.| )/gi,"avenida ");
    item = item.replace(/(avd|av)(\.| )/gi,"avenida ");
    item = item.replace(/bo(\.| )/gi,"barrio ");
    item = item.replace(/blvr(\.| )/gi,"bulevar ");
    item = item.replace(/pje(\.| )/gi,"pasaje ");
    item = item.replace(/cton(\.| )/gi,"cantón ");
    item = item.replace(/col(\.| )/gi,"colonia ");
    item = item.replace(/cas(\.| )/gi,"casa ");
    item = item.replace(/  /gi," ");
    return item;
}

export function testDefinition(){
    return "Prueba de concepto 345";
}

export function isValidEmail(email){
    return String(email)
    .toLowerCase()
    .match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
}