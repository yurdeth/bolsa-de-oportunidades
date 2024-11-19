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
    <div
        class="login-container d-flex flex-column align-items-center justify-content-center"
    >
        <!-- Logo y Título -->
        <img
            src="../../img/Logo_Nuevo.png"
            alt="Logo Facultad"
            class="logo-facultad mb-4"
        />
        <h2 class="text-center mb-4">Bienvenido a Bolsa de Oportunidades</h2>

        <div
            class="loader login-card"
            v-if="loading"
            style="
                width: 100%;
                height: 300px;
                display: flex;
                justify-content: center;
                align-items: center;
            "
        >
            <div
                class="spinner-border text-danger"
                role="status"
                style="width: 100px; height: 100px"
            >
                <span class="sr-only">Cargando...</span>
            </div>
        </div>

        <!-- Formulario -->
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
                <span
                    v-if="errors.email"
                    class="invalid-feedback d-block"
                    role="alert"
                >
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
                        <span
                            class="input-group-text toggle-password"
                            @click="togglePassword"
                        >
                            <i
                                :class="
                                    showPassword
                                        ? 'fa fa-eye'
                                        : 'fa fa-eye-slash'
                                "
                            ></i>
                        </span>
                    </div>
                </div>
                <span
                    v-if="errors.password"
                    class="invalid-feedback d-block"
                    role="alert"
                >
                    <strong>{{ errors.password[0] }}</strong>
                </span>
            </div>

            <div class="form-group d-flex justify-content-between">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember"
                    />
                    <label class="form-check-label" for="remember"
                        >Recordarme</label
                    >
                </div>
                <a href="/reset-password" class="text-danger"
                    >¿Ha olvidado tu contraseña?</a
                >
            </div>

            <button
                type="submit"
                class="btn btn-danger btn-block rounded-pill my-3"
            >
                Ingresar
            </button>

            <div class="text-center mt-3">
                <p>
                    ¿No estás registrado?
                    <a href="/register" class="text-danger">Regístrate Aquí</a>
                </p>
            </div>
        </form>
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
            errors: {}, // Inicializado como un objeto vacío
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
    },
    methods: {
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        async login() {
            this.loading = true;
            try {
                const response = await api.post("/login", this.form);
                console.log(response);
                localStorage.setItem("token", response.data.data.token);
                localStorage.setItem(
                    "user",
                    JSON.stringify(response.data.data.user)
                );
                api.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${response.data.data.token}`;
                this.$router.push("/dashboard");
            } catch (error) {
                // Manejo de errores desde el servidor
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    this.errors = {
                        email: [
                            "Ha ocurrido un error. Por favor, inténtelo de nuevo.",
                        ],
                    };
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
