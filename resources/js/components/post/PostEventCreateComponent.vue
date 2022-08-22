<template>
    <form novalidate ref="frmCreatePostEvent" class="card mb-3 alterCard">
        <div class="card-header bg-secondary alterHeader">
            <span>{{ PlaceholderMsg2 }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 titleContainer mb-2">
                            <img
                                width="60px"
                                height="60px"
                                style="object-fit: cover"
                                class="img-circle img-bordered-sm"
                                :src="itemData.creator.profile_img"
                                alt="user image"
                            />
                            <div
                                style="width: 100%; margin-left: 10px"
                                class="form-group mb-0"
                            >
                                <input
                                    v-model="itemData.post.title"
                                    v-bind:placeholder="PlaceholderTitle"
                                    maxlength="100"
                                    minlength="2"
                                    required
                                    width="100%"
                                    type="text"
                                    style="margin-bottom: 0px"
                                    class="form-control"
                                />
                                <div class="invalid-feedback">
                                    Título requerido
                                </div>                                
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-4 col-md-4">
                                    <div
                                        class="form-group mb-0"
                                        v-if="itemData.post.type == 'event'"
                                    >
                                        <label
                                            for="exampleFormControlTextarea1"
                                            class="text-muted"
                                            >Fecha/hora</label
                                        >

                                        <DatePicker
                                            v-model="eventDate"
                                            type="datetime"
                                            confirm
                                            format="DD/MM/YYYY hh:mm a"
                                            :clearable="false"
                                            :editable="false"
                                        ></DatePicker>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4 col-md-4">
                                    <div
                                        class="form-group mb-0"
                                        v-if="itemData.post.type == 'event'"
                                    >
                                        <label
                                            for="exampleFormControlTextarea1"
                                            class="text-muted"
                                            >Se repite</label
                                        >
                                        <select
                                            v-model="itemData.dtl_event.frequency"
                                            class="custom-select"
                                        >
                                            <option selected value="unique">
                                                No se repite
                                            </option>
                                            <option value="repeat">
                                                Cada año
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4">
                                    <div
                                        class="form-group mb-0"
                                        v-if="itemData.post.type == 'event'"
                                    >
                                        <label
                                            for="exampleFormControlTextarea1"
                                            class="text-muted"
                                            >Categoria</label
                                        >
                                        <select
                                            v-model="itemData.dtl_event.has_cost"
                                            class="custom-select"
                                        >
                                            <option :value="true">
                                                Pagado
                                            </option>
                                            <option :value="false">
                                                Gratuito
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div
                                    v-if="itemData.post.type == 'event' && itemData.dtl_event.has_cost"
                                    class="col-12 col-lg-12 col-md-12"
                                >
                                    <div class="form-group mb-0">
                                        <label
                                            for="exampleFormControlTextarea1"
                                            class="text-muted"
                                            data-toggle="tooltip"
                                            data-placement="right"
                                            title="Informa a los asistentes que tu evento tendra un valor definido"
                                            >Digite el precio
                                            <svg
                                                data-toggle="tooltip"
                                                data-placement="right"
                                                title="Informa a los asistentes que tu evento tendra un valor especificado"
                                                width="1em"
                                                height="1em"
                                                viewBox="0 0 16 16"
                                                class="bi bi-info-circle-fill"
                                                fill="currentColor"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"
                                                />
                                            </svg>
                                        </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">$</span>
                                            </div>
                                            <input
                                                v-model="itemData.dtl_event.cost"
                                                type="number"
                                                min="0"
                                                class="form-control"
                                                placeholder="precio"
                                                aria-label="Username"
                                                aria-describedby="basic-addon1"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-lg-4 col-md-4">
                                    <div class="form-group mb-0">
                                        <label
                                            :for="'eventMuni' + itemData.post.id"
                                            class="text-muted"
                                            >Municipio
                                        </label>
                                        <select
                                            class="custom-select"
                                            :id="'eventMuni' + itemData.post.id"
                                            ref="itemMuniSelect"
                                            v-model="itemData.dtl_event.address.municipio_id"
                                        >
                                            <option disabled value="0">
                                                - - Municipio - -
                                            </option>
                                            <option
                                                v-for="muni in municipios"
                                                :value="muni.id"
                                                :key="muni.id"
                                            >
                                                {{ muni.municipio }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-8 col-md-8">
                                    <div class="form-group mb-0">
                                        <label
                                            :for="'eventDepto' + itemData.post.id"
                                            class="text-muted"
                                            >Detalle de ubicacion
                                        </label>
                                        <input
                                            v-model="itemData.dtl_event.address.details"
                                            placeholder="Calle, Barrio, Colonia, etc."
                                            maxlength="100"
                                            minlength="2"
                                            required
                                            class="form-control"
                                        />
                                    <div class="invalid-feedback">
                                        Detalle requerido
                                    </div>                                          
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-1">
                                <textarea
                                    v-model="itemData.post.description"
                                    minlength="2"
                                    maxlength="800"
                                    required
                                    style="resize: none"
                                    class="form-control"
                                    v-bind:placeholder="PlaceholderMsg1 +itemData.creator.nickname +'?'"
                                    rows="3"
                                ></textarea>
                                <div class="invalid-feedback">
                                    Descripción requerida
                                </div>   
                            </div>
                        </div>
                        <div class="col-12">
                            <MediaComponent 
                            @drop-ids="setMediaDrops"
                            :item-data="{ media: itemData.media }"/>
                        </div>
                        <div class="col-12">
                            <br />
                            <button
                                style="width: 100%; cursor: pointer"
                                type="button"
                                :disabled="flags.upsertContent"
                                @click="onSave"
                                class="btn btn-success btn-block"
                            >
                                <span
                                    v-if="flags.upsertContent"
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import moment from 'moment';
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import "vue2-datepicker/locale/es";
import MediaComponent from "./MediaComponent.vue";
import {
    upsertPostEvent,
    getMunicipios,
    getGeo,
    getPlaces,
} from "../../service";
import { directionsTokens } from "../../utils";

export default {
    components: { DatePicker, MediaComponent },
    props: {
        pdata: { type: Object, required: true },
        editMode: { type: Boolean, default: false },
    },
    data: function () {
        return {
            flags: {
                upsertContent: false,
            },
            itemData: JSON.parse(JSON.stringify(this.pdata)),
            municipios: [],
            acAppData: window.obj_ac_app,
            eventDate: null
        };
    },
    created: function () {
        getMunicipios().then((response) => {
            this.municipios = response.data;
        });
    },
    mounted: function () {
        this.loadLocalValues();
    },
    computed: {
        PlaceholderMsg1: function () {
            return this.itemData.post.type == "event"
                ? "Explica de qué trata tu evento..."
                : "¿Qué estás pensado ";
        },
        PlaceholderMsg2: function () {
            return this.itemData.post.type == "event"
                ? "Evento"
                : "Publicacion";
        },
        PlaceholderTitle: function () {
            return this.itemData.type === "event"
                ? "Lugar del evento"
                : "Título de la publicación";
        },
    },
    methods: {
        loadLocalValues: function () {
            if(this.itemData.dtl_event.event_date != undefined){
                this.eventDate = new Date(this.itemData.dtl_event.event_date);
            }else{
                this.eventDate = moment().add(2, 'days').toDate();
            }
        },
        setMediaDrops: function(arr){
            this.itemData.mediadrop_ids = JSON.parse(JSON.stringify(arr));
        },        
        onSave: function () {
             if (!this.$refs.frmCreatePostEvent.checkValidity()) {
                 this.$refs.frmCreatePostEvent.classList.add("was-validated");
                return;
             }            

            if (this.itemData.dtl_event.address.municipio_id == 0) {
                StatusHandler.ValidationMsg("Municipio no seleccionado");
                return;
            }

            this.itemData.dtl_event.event_date = moment(this.eventDate).format('YYYY-MM-DD HH:mm:ss');
            this.upsert();
        },
        upsert: async function () {
            this.flags.upsertContent = true;
            /**Maps Places and Geodecoding */
            const municipio =this.$refs.itemMuniSelect.options[this.$refs.itemMuniSelect.options.selectedIndex].text;
            const depto = "Sonsonate";
            var geoLat = 0;
            var geoLng = 0;
            var placeResponse = null;
            var geoResponse = null;

            try{
                const direction = directionsTokens(`${this.itemData.dtl_event.address.details}, ${municipio} , ${depto}, El Salvador`);
                placeResponse = await getPlaces(direction);
                //axios status | controller api result status
                if (placeResponse.status != 200 || placeResponse.data.data.status !== "OK") {
                    geoResponse = await getGeo(direction);//Conmsultando API Geodecoding
                }

                if (geoResponse != null) {
                    //Si es diferente de null significa que ubo necesidad de consultar la api geo
                    if (geoResponse.data.data.status === "OK") {
                        geoLat = geoResponse.data.data.results[0].geometry.location.lat;
                        geoLng = geoResponse.data.data.results[0].geometry.location.lng;
                    }
                } else {
                    //Si es null significa que no ubo necesidad de consultar la api geo
                    //Ya no se valida ZERO_RESULT u otro error porque se consulto api feo para el caso
                    geoLat = placeResponse.data.data.candidates[0].geometry.location.lat;
                    geoLng = placeResponse.data.data.candidates[0].geometry.location.lng;
                }             
                this.itemData.dtl_event.is_geo = true;
            }catch(ex){
                geoLat = 0;
                geoLng = 0;
                this.itemData.dtl_event.is_geo = false;
            }

            //mario.nerio: Si no se pudo resolver el places o el geodecoding se guardan coordenadas 0,0
            this.itemData.dtl_event.geo.lat = geoLat;
            this.itemData.dtl_event.geo.lng = geoLng;

            upsertPostEvent(this.itemData)
                .then((result) => {
                    let response = result.data;
                    if (response.code == 0) {
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }

                    this.$emit("saved", response.data);
                })
                .catch((ex) => {
                    const target_process = "Guarda informacion de elemento";
                    StatusHandler.Exception(target_process, ex);
                })
                .finally(e=>{
                    this.flags.upsertContent = false;
                })
        },
    },
};
</script>

<style scoped>
.alterHeader {
    border-top-left-radius: 0px !important;
    border-top-right-radius: 0px !important;
}

.alterCard {
    max-width: 600px;
    width: 100%;
    margin: auto;
}

.loader,
.loader:before,
.loader:after {
    background: #ffffff;
    -webkit-animation: load1 1s infinite ease-in-out;
    animation: load1 1s infinite ease-in-out;
    width: 1em;
    height: 4em;
}
.loader {
    color: #ffffff;
    text-indent: -9999em;
    margin: 88px auto;
    position: relative;
    font-size: 11px;
    -webkit-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-animation-delay: -0.16s;
    animation-delay: -0.16s;
}
.loader:before,
.loader:after {
    position: absolute;
    top: 0;
    content: "";
}
.loader:before {
    left: -1.5em;
    -webkit-animation-delay: -0.32s;
    animation-delay: -0.32s;
}
.loader:after {
    left: 1.5em;
}
@-webkit-keyframes load1 {
    0%,
    80%,
    100% {
        box-shadow: 0 0;
        height: 4em;
    }
    40% {
        box-shadow: 0 -2em;
        height: 5em;
    }
}
@keyframes load1 {
    0%,
    80%,
    100% {
        box-shadow: 0 0;
        height: 4em;
    }
    40% {
        box-shadow: 0 -2em;
        height: 5em;
    }
}

.frontPanelSending {
    background-color: white;
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0px;
    left: 0px;
    z-index: 90;
    opacity: 0.5;
    display: flex;
    justify-content: center;
    align-items: center;
}

.titleContainer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mx-input {
    height: calc(2.25rem + 2px) !important;
    padding: 0.375rem 0.75rem !important;
    font-size: 1rem !important;
}

.mx-datepicker {
    display: block !important;
    width: 100% !important;
}
</style>
