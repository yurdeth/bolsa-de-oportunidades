<style scoped>
/* Importar fuente */
@import url("https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap");

/* Estilos generales */
body {
    background-color: #f7f7f7;
    font-family: "Libre Baskerville", serif;
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    margin-bottom: 1rem !important;
    background-image: none !important;
    border-color: #8b0000 !important;
}

.login-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.logo-facultad {
    max-width: 100px;
}

h2 {
    font-size: 1.5rem;
    color: #8b0000;
    font-weight: bold;
}

.login-card {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 800px;
    max-width: 1200px;
    padding: 20px;
}

.form-label {
    font-weight: bold;
    color: #333;
    font-size: 0.9rem;
}

.form-control {
    border: none;
    border-bottom: 2px solid #ddd;
    padding: 10px;
    font-size: 0.9rem;
    background-color: transparent;
}

.form-control:focus {
    border-bottom: 2px solid #8b0000;
    outline: none;
}

.input-group-text {
    background-color: transparent;
    border: none;
    color: #8b0000;
}

.btn-danger {
    background-color: #8b0000;
    font-size: 1rem;
    padding: 10px 5px;
}

.btn-danger:hover {
    background-color: #a11111;
}

.text-danger {
    font-weight: bold;
    color: #8b0000;
}

.text-danger:hover {
    text-decoration: underline;
}
</style>

<template>
    <div class="login-container d-flex flex-column align-items-center justify-content-center">
        <!-- Logo y Título -->
        <img src="../../img/Logo_Nuevo.png" alt="Logo Facultad" class="logo-facultad mb-4"/>
        <h2 class="text-center mb-4">Bienvenido a Bolsa de Oportunidades</h2>

        <div class="loader login-card" v-if="loading"
             style="width: 100%; height: 300px; display: flex; justify-content: center; align-items: center;">
            <div class="spinner-border text-danger" role="status" style="width: 100px; height: 100px">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>

        <!-- Formulario -->
        <form class="login-card" @submit.prevent="register" v-if="!loading">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="nombre" class="form-label">Nombre comercial</label>
                        <div class="input-group">
                            <input id="nombre" type="text" class="form-control" :class="{ 'is-invalid': errors.nombre }"
                                   name="nombre" placeholder="Ingrese el nombre comercial de su empresa"
                                   v-model="form.nombre" required autofocus/>
                        </div>
                        <span v-if="errors.nombre" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.nombre[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <input id="email" type="email" class="form-control" :class="{ 'is-invalid': errors.email }"
                                   name="email" placeholder="Ingrese su correo" v-model="form.email" required
                                   autofocus/>
                        </div>
                        <span v-if="errors.email" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.email[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="direccion" class="form-label">Dirección</label>
                        <div class="input-group">
                            <input id="direccion" type="text" class="form-control"
                                   :class="{ 'is-invalid': errors.direccion }" name="direccion"
                                   placeholder="Ingrese la ubicación de su empresa" v-model="form.direccion" required
                                   autofocus/>
                        </div>
                        <span v-if="errors.direccion" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.direccion[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <div class="input-group">
                            <input id="telefono" type="text" class="form-control"
                                   :class="{ 'is-invalid': errors.telefono }" name="telefono"
                                   placeholder="Ingrese el teléfono de su empresa" v-model="form.telefono" required
                                   autofocus/>
                        </div>
                        <span v-if="errors.telefono" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.telefono[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="sitio_web" class="form-label">Sitio Web</label>
                        <div class="input-group">
                            <input id="sitio_web" type="text" class="form-control"
                                   :class="{ 'is-invalid': errors.sitio_web }" name="sitio_web"
                                   placeholder="Ingrese el sitio web de su empresa" v-model="form.sitio_web" required
                                   autofocus/>
                        </div>
                        <span v-if="errors.sitio_web" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.sitio_web[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="id_sector" class="form-label">Sector de la industria</label>
                        <div class="input-group">
                            <select id="id_sector" class="form-control" :class="{ 'is-invalid': errors.id_sector }"
                                    name="id_sector" v-model="form.id_sector" required>
                                <option value="" disabled>Seleccione un sector</option>
                                <option v-for="sector in sectors" :key="sector.id" :value="sector.id">{{
                                        sector.nombre
                                    }}
                                </option>
                            </select>
                        </div>
                        <span v-if="errors.id_sector" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.id_sector[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <div class="input-group">
                            <textarea id="descripcion" type="text" class="form-control"
                                      :class="{ 'is-invalid': errors.descripcion }" name="descripcion"
                                      placeholder="Ingrese una descripción de su empresa" v-model="form.descripcion"
                                      required autofocus></textarea>
                        </div>
                        <span v-if="errors.descripcion" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.descripcion[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="logo_url" class="form-label">Logo</label>
                        <div class="input-group">
                            <input id="logo_url" type="file" class="form-control"
                                   :class="{ 'is-invalid': errors.logo_url }" name="logo_url"
                                   placeholder="Ingrese el logo de su empresa" v-on:change="form.logo_url"
                                   autofocus/>
                        </div>
                        <span v-if="errors.logo_url" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.logo_url[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input id="password" :type="showPassword ? 'text' : 'password'" class="form-control"
                                   :class="{ 'is-invalid': errors.password }" name="password"
                                   placeholder="Ingrese su contraseña" v-model="form.password" required/>
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" @click="togglePassword">
                                    <i :class="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
                                </span>
                            </div>
                        </div>
                        <span v-if="errors.password" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.password[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="password_confirmation" class="form-label">Repita su contraseña</label>
                        <div class="input-group">
                            <input id="password_confirmation" :type="showPassword ? 'text' : 'password'"
                                   class="form-control" :class="{ 'is-invalid': errors.password_confirmation }"
                                   name="password_confirmation" placeholder="Repita su contraseña"
                                   v-model="form.password_confirmation" required/>
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" @click="togglePassword">
                                    <i :class="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
                                </span>
                            </div>
                        </div>
                        <span v-if="errors.password_confirmation" class="invalid-feedback d-block" role="alert">
                            <strong>{{ errors.password_confirmation[0] }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-danger btn-block rounded-pill my-3">Ingresar</button>

            <div class="text-center mt-3">
                <p>¿Ya tienes una cuenta? <a href="/login" class="text-danger">Iniciar Sesión</a></p>
            </div>
        </form>
    </div>
</template>

<script>
import {api} from "../../api";

export default {
    data() {
        return {
            showPassword: false,
            loading: false,
            form: {
                nombre: "",
                email: "",
                direccion: "",
                telefono: "",
                sitio_web: "",
                id_sector: "",
                descripcion: "",
                logo_url: "",
                password: "",
                password_confirmation: "",
                id_tipo_usuario: 4,
                verificada: true
            },
            errors: {}, // Inicializado como un objeto vacío
            sectors: [],
        };
    },
    async mounted() {
        // Verificar si el usuario ya está autenticado
        this.loading = true;
        if (localStorage.getItem("token")) {
            try {
                let response = await api.post("/access_token", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
                localStorage.setItem("token", response.data.data.token);
                localStorage.setItem(
                    "user",
                    JSON.stringify(response.data.data.user)
                );
                this.$router.push("/dashboard");
                this.loading = false;
            } catch (error) {
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                this.loading = false;
            }
        } else {
            this.loading = false;
        }

        await this.loadSectors();
    },
    methods: {
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        async register() {
            this.loading = true;
            try {
                let response = await api.post("/register", this.form);
                localStorage.setItem("token", response.data.data.token);
                localStorage.setItem("user", JSON.stringify(response.data.data.user));
                this.$router.push("/dashboard");
            } catch (error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error("An unexpected error occurred:", error);
                }
            } finally {
                this.loading = false;
            }
        },
        async loadSectors() {
            try {
                let response = await api.get("/sectores-industria");
                this.sectors = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>
