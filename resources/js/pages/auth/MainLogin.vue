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
    max-width: 170px;
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
    width: 100%;
    max-width: 400px;
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
        <img src="../../img/Logo_Nuevo.png" alt="Logo Facultad" class="logo-facultad mb-4" />
        <h2 class="text-center mb-4">Bienvenido a Bolsa de Oportunidades</h2>

        <div
            class="loader login-card"
            v-if="loading"
            style="width: 100%; height: 300px; display: flex; justify-content: center; align-items: center;"
        >
            <div class="spinner-border text-danger" role="status" style="width: 100px; height: 100px">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>

        <form class="login-card" @submit.prevent="login" v-if="!loading">
            <div class="form-group mb-4">
                <label for="email" class="form-label">Correo Electrónico</label>
                <div class="input-group">
                    <input
                        id="email"
                        type="email"
                        class="form-control"
                        :class="{ 'is-invalid': errors.email }"
                        name="email"
                        placeholder="Ingrese su correo"
                        v-model="form.email"
                        required
                        autofocus
                    />
                </div>
                <span v-if="errors.email" class="invalid-feedback d-block" role="alert">
                    <strong>{{ errors.email[0] }}</strong>
                </span>
            </div>

            <div class="form-group mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="form-control"
                        :class="{ 'is-invalid': errors.password }"
                        name="password"
                        placeholder="Ingrese su contraseña"
                        v-model="form.password"
                        required
                    />
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

            <div class="form-group d-flex justify-content-between flex-wrap">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <a href="#" class="text-danger" @click.prevent="openModal">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn btn-danger btn-block rounded-pill my-3">
                Ingresar
            </button>

            <div class="text-center mt-3">
                <p>
                    ¿No estás registrado?
                    <a href="/register" class="text-danger">Regístrate Aquí</a>
                </p>
            </div>
        </form>

        <!-- Modal -->
        <div
        class="modal fade"
        tabindex="-1"
        :class="{ show: showModal }"
        style="display: block; background: rgba(0, 0, 0, 0.5)"
        v-if="showModal"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Contraseña</h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="closeModal"
                    ></button>
                </div>
                <form @submit.prevent="sendResetCode">
                <div class="modal-body">
                    <p>Ingresa tu correo para recuperar tu contraseña:</p>
                    <div class="form-group">
                        <div>
                            <label for="email">Correo electrónico</label>
                            <input class="form-control"
                            v-model="resetEmail"
                            type="email"
                            id="emailR"
                            placeholder="Ingresa tu correo electrónico"
                            required
                            />
                        </div>

                        <p v-if="message" class="text-success">{{ message }}</p>
                        <p v-if="error" class="text-danger">{{ modalError }}</p>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Enviar
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>


    </div>
</template>

<script>
import { api } from "../../api";

export default {
    data() {
        return {
            showPassword: false,
            loading: false,
            form: {
                email: "",
                password: "",
            },
            errors: {},
            recoveryEmail: "",
            showModal: false,
            resetEmail: "",
            modalError: null,
            emailR: '',
            message: '',
            error: '',
        };
    },
    methods: {
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        async login() {
            this.loading = true;
            try {
                const response = await api.post("/login", this.form);
                localStorage.setItem("token", response.data.data.token);
                localStorage.setItem("user", JSON.stringify(response.data.data.user));
                api.defaults.headers.common["Authorization"] = `Bearer ${response.data.data.token}`;
                this.$router.push("/dashboard");
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    this.errors = { email: ["Error, inténtelo de nuevo."] };
                }
            } finally {
                this.loading = false;
            }
        },
        openModal() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.resetEmail = "";
            this.modalError = null;
        },
        async sendResetCode() {
            this.loading = true;
            try {
                const response = await api.post("/verifyEmail", { email: this.resetEmail });

                if (response.data.status == true) {
                    console.log('verificado');
                    this.$router.push({ name: 'changePassword', query: { email: this.resetEmail } });
                } else {
                    console.log('e');
                    this.modalError = "Este correo no está registrado.";
                }
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.modalError = error.response.data.errors[0];
                } else {
                    this.modalError = "Hubo un error al intentar verificar el correo. Inténtelo de nuevo.";
                }
            } finally {
                this.loading = false;
            }
        }
    },
};
</script>