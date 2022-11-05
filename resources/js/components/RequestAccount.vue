<template>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 titleContainer">
                    <form
                        id="frmDataRequest"
                        novalidate
                        ref="frmRequestAccount"
                    >
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="raCompleteName"
                                    >Nombre propietario cuenta</label
                                >
                                <label class="sr-only" for="raCompleteName"
                                    >Nombre propietario cuenta</label
                                >
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-address-book"></i>
                                        </div>
                                    </div>
                                    <input
                                        v-model="fullname"
                                        type="text"
                                        required
                                        maxlength="200"
                                        minlength="2"
                                        class="form-control"
                                        id="raCompleteName"
                                        aria-describedby="emailHelp"
                                    />
                                    <div class="invalid-feedback">
                                        Ingrese su nombre completo
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-6">
                                <label for="raEmail">Correo electrónico</label>
                                <label class="sr-only" for="raEmail"
                                    >Correo electrónico</label
                                >
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-at"></i>
                                        </div>
                                    </div>
                                    <input
                                        v-model="correo"
                                        @keyup="flags.email_exists = false"
                                        type="email"
                                        required
                                        maxlength="255"
                                        minlength="2"
                                        class="form-control"
                                        id="raEmail"
                                        placeholder="Ej. example@email.com"
                                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                        aria-describedby="emailHelp"
                                    />
                                    <div class="invalid-feedback">
                                        Ingrese su correo electrónico
                                    </div>
                                    <div
                                        v-if="flags.email_exists"
                                        style="
                                            width: 100%;
                                            margin-top: 0.25rem;
                                            font-size: 80%;
                                            color: #dc3545;
                                            font-weight: bold;
                                        "
                                    >
                                        El correo electrónico ya ha sido
                                        registrado
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-6">
                                <label for="raTelephone"
                                    >Número telefónico</label
                                >
                                <label class="sr-only" for="raTelephone"
                                    >Número telefónico
                                </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i
                                                class="fas fa-phone-square-alt"
                                            ></i>
                                        </div>
                                    </div>
                                    <input
                                        v-model="tel"
                                        v-on:input="validPatternTel"
                                        type="tel"
                                        required
                                        maxlength="9"
                                        minlength="2"
                                        class="form-control"
                                        placeholder="Ej. 7777-7777"
                                        id="raTelephone"
                                        aria-describedby="emailHelp"
                                    />
                                    <div class="invalid-feedback">
                                        Ingrese su número de contacto
                                    </div>
                                    <div
                                        v-if="flags.telephone_exist"
                                        style="
                                            width: 100%;
                                            margin-top: 0.25rem;
                                            font-size: 80%;
                                            color: #dc3545;
                                            font-weight: bold;
                                        "
                                    >
                                        El número de teléfono ya ha sido
                                        registrado
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-6">
                                <label for="raNameArt">Nombre artístico</label>
                                <label class="sr-only" for="raNameArt"
                                    >Nombre artístico
                                </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-paint-brush"></i>
                                        </div>
                                    </div>
                                    <input
                                        required
                                        v-model="artistic"
                                        type="tel"
                                        maxlength="100"
                                        minlength="2"
                                        class="form-control"
                                        id="raNameArt"
                                        aria-describedby="emailHelp"
                                    />
                                    <div class="invalid-feedback">
                                        Ingrese su nombre artístico
                                    </div>
                                </div>
                                <small
                                    id="emailHelp"
                                    class="form-text text-muted"
                                    >*Escribe el nombre que te representa como
                                    artista/banda/entidad.</small
                                >
                            </div>

                            <div class="form-group col-6">
                                <label for="raRubro">Seleccionar rubro</label>
                                <label class="sr-only" for="raRubro"
                                    >Seleccionar rubro</label
                                >
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-fingerprint"></i>
                                        </div>
                                    </div>
                                    <select
                                        required
                                        v-model="rubro"
                                        class="custom-select"
                                        id="raRubro"
                                    >
                                        <option disabled value="">Elegir</option>
                                        <optgroup v-for="(main, key) in listTags"
                                            v-bind:key="key"
                                            :label="key">
                                            <option
                                                v-for="(item, i) in main"
                                                v-bind:key="i"
                                                :value="item.id"
                                            >
                                                {{ item.tag }}
                                            </option>
                                        </optgroup>
                                    </select>
                                    <div class="invalid-feedback">
                                        Especifique su especialidad
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                                :disabled="isSendData">
                                Cancelar
                            </button>
                            <button
                                @click="onSubmit"
                                class="btn btn-primary"
                                type="button"
                                :disabled="isSendData"
                            >
                                <span
                                    v-if="isSendData"
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                <template v-if="!isSendData">Enviar Solicitud</template>
                                <template v-else>Enviando …</template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
const { util, getTags, requestAccount } = require("../api/api.service");
export default {
    data() {
        return {
            isSendData: false,
            flags: {
                email_exists: false,
                telephone_exist: false,
            },
            listTags: [],
            rubro: "",
            fullname: "",
            correo: "",
            tel: "",
            artistic: "",
        };
    },
    beforeMount() {
        getTags().then((tags) => {
            this.listTags = tags.data;
        }).catch((e) => {
                StatusHandler.Exception("Recuperar rubros", ex);
        });
    },
    mounted: function () {
        if (document.getElementById("openRegister").value === "true") {
            $("#requestAccountModal").modal();
        }
    },
    methods: {
        checkExistData: function () {
            //Verificar si existe otro registro con ese correo, numero de telefono o email
            var temp = {
                code: 0,
                data: {
                    exist_email: false,
                    exist_telephone: false,
                },
                msg: "",
            };

            return new Promise((resolve, reject) => {
                axios.get(`/api/user/checkEmail/${this.correo.trim()}`).then((result) => {
                        let response = result.data;
                        if (response.code == 0) {
                            temp.msg ="Ocurrio un error interno en la verificacion de correo, contacte Soporte Tecnico";
                            resolve(temp);
                        }

                        //here code is 1
                        if (response.data.code == 2) {
                            temp.data.exist_email = true;
                        }
                        return axios.get(`/api/user/existTelephone/${-1}/${this.tel.trim()}`);
                }).then((result) => {
                        let response2 = result.data;
                        if (response2.code == 0) {
                            temp.msg ="Ocurrio un error interno en la verificacion de correo, contacte Soporte Tecnico";
                            resolve(temp);
                        }
                        //here code is 1
                        if (response2.data == 1) {
                            temp.data.exist_telephone = true;
                        }
                        temp.code = 1;
                        resolve(temp);
                });
            });
        },
        validPatternTel: function () {
            this.flags.telephone_exist = false;
            if (this.tel.length == 5 && this.tel[4] !== "-") {
                if (!isNaN(parseInt(this.tel[4]))) {
                    this.tel = this.tel.substring(0, 4) + "-" + this.tel[4];
                } else {
                    this.tel = this.tel.substring(0, 4) + "-";
                }
            }
            if (this.tel.length < 5) {
                if (isNaN(parseInt(this.tel))) {
                    this.tel = "";
                } else {
                    this.tel = parseInt(this.tel);
                }
            }

            if (this.tel.length > 5) {
                if (isNaN(parseInt(this.tel.substr(5)))) {
                    this.tel = this.tel.substring(0, 5);
                } else {
                    this.tel =
                        this.tel.substring(0, 5) + parseInt(this.tel.substr(5));
                }
            }
        },
        onSubmit: async function () {
            if (this.$refs.frmRequestAccount.checkValidity() !== false) {
                if (this.rubro == undefined || this.rubro == 0 || isNaN(parseInt(this.rubro))) {
                    StatusHandler.StatusToast(StatusHandler.TOAST_STATUS.FAIL,"Debe seleccionar una espacialidad/rubro artístico");
                    return;
                }
                this.isSendData = true;
                //Verificate if email or telephone already exist
                var response = await this.checkExistData();
                if (response.code == 0) {
                    $("#requestAccountModal").modal("hide");
                    StatusHandler.ShowStatus(
                        response.msg,
                        StatusHandler.OPERATION.DEFAULT,
                        StatusHandler.STATUS.FAIL
                    );
                }

                this.flags.email_exists = response.data.exist_email;
                this.flags.telephone_exist = response.data.exist_telephone;

                if (!this.flags.email_exists && !this.flags.telephone_exist) {
                    this.SolicitarCuenta();
                } else {
                    this.isSendData = false;
                }
            } else {
                this.$refs.frmRequestAccount.classList.add("was-validated");
            }
        },
        SolicitarCuenta() {
            const data = {
                name: this.fullname.trim(),
                email: this.correo.trim(),
                telephone: this.tel.trim(),
                rubros: this.rubro,
                artistic_name: this.artistic.trim(),
            };

            axios.post(`/api/requestaccounts`, data).then((result) => {
                    let response = result.data;
                    if (response.code == 0) {
                        
                        //Si error viene vacio y el response code es 0 entonces es otro tipo de error
                        if(response.errors == undefined){
                            StatusHandler.ShowStatus("Error en el proceso de ingreso de usuario, consulte soporte técnico",StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                            return;
                        }

                        if (response.errors.email != undefined) {
                            this.flags.email_exists = true;
                            StatusHandler.StatusToast(
                                StatusHandler.TOAST_STATUS.FAIL,
                                response.errors.email[0]
                            );
                            //se omite el return para que pueda llegar al slguiente
                        }

                        if (response.errors.telephone != undefined) {
                            this.flags.telephone_exist = true;
                            StatusHandler.StatusToast(
                                StatusHandler.TOAST_STATUS.FAIL,
                                response.errors.telephone[0]
                            );
                            return;
                        }

                        StatusHandler.StatusToast(
                            StatusHandler.TOAST_STATUS.FAIL,
                            response.msg
                        );
                        return;
                    }
                    //Clean and redirect
                    let email_new_user = this.correo;
                    this.rubro = 0;
                    this.fullname = "";
                    this.correo = "";
                    this.tel = "";
                    this.artistic = "";
                    this.email_exists = false;

                    window.location.href =obj_ac_app.base_url +`/email/status/${email_new_user.trim()}`;
                })
                .catch((ex) => {
                    $("#requestAccountModal").modal("hide");
                    StatusHandler.Exception("Solicitar cuenta de usuario", ex);
                })
                .finally(() => {
                    this.isSendData = false;
                });
        },
    },
};
</script>
