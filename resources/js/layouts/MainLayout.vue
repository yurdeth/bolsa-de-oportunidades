<style>
.loader-container {
    width: 100%;
    height: 100vh;
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.contenedor {
    display: grid;
    grid-template-columns: 300px 1fr;
}

nav {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background-color: #8b0000;
    color: white;
    padding: 20px;
    height: 100vh;
}

.nav-mobile {
    display: none;
    height: 4rem;
}

.avatar {
    margin-top: 2.5rem;
    text-align: center;
    margin-bottom: 2.5rem;
}

.avatar h3 {
    margin-top: 1rem;
    font-size: 1.5rem;
}

.avatar h4 {
    font-size: 0.95rem;
    color: #f0f0f0;
}

.avatar h5 {
    font-size: 0.9rem;
    color: #f0f0f0;
}

.avatar img {
    max-width: 100px;
    border-radius: 50%;
    background-color: #fff;
}

.links {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    height: 500px;
    overflow-y: auto;
}

.links::-webkit-scrollbar {
    display: none;
}

.links a,
.links button {
    background-color: #8b0000;
    border: none;
    color: white;
    text-decoration: none;
    margin-bottom: 1rem;
    padding: 10px 20px;
    text-align: left;
    transition: background-color 0.3s;
}

.links a:hover,
.links button:hover {
    background-color: #fff;
    color: #8b0000;
    font-weight: bold;
}

.links a.router-link-active,
.links a.router-link-exact-active {
    background-color: #fff;
    color: #8b0000;
    font-weight: bold;
}

.links a.router-link-active:hover,
.links a.router-link-exact-active:hover {
    background-color: #595959ff;
    color: white;
    font-weight: bold;
}

.menu-button {
    background-color: transparent;
    box-shadow: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
}

.nav-desktop.show {
    transform: translateX(0);
}

.nav-desktop .menu-button {
    display: none;
}

@media screen and (max-width: 1024px) {
    .nav-desktop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: 1000;
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .nav-mobile {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .nav-desktop .menu-button {
        display: block;
    }

    .contenedor {
        grid-template-columns: 1fr;
    }

    main {
        padding: 20px;
    }
}
</style>

<template>
    <div v-if="loading">
        <div
            class="loader-container d-flex justify-content-center align-items-center"
        >
            <div
                class="spinner-border text-danger"
                role="status"
                style="width: 100px; height: 100px"
            >
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
    </div>
    <div v-if="!loading" class="contenedor">
        <nav :class="['nav-desktop', { show: showMenu }]">
            <div>
                <div
                    style="
                        width: 100%;
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                    "
                >
                    <button class="menu-button" @click="toggleMenu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="avatar">
                    <img :src="getPhotoAvata()" alt="Logo Facultad" />
                    <h3>
                        {{
                            user_data.info_coordinador.length > 0
                                ? user_data.info_coordinador[0].nombres +
                                  " " +
                                  user_data.info_coordinador[0].apellidos
                                : user_data.info_estudiante.length > 0
                                ? user_data.info_estudiante[0].nombres +
                                  " " +
                                  user_data.info_estudiante[0].apellidos
                                : user_data.info_empresa.length > 0
                                ? user_data.info_empresa[0].nombre
                                : "Administrador"
                        }}
                    </h3>
                    <h4>{{ user_data.email }}</h4>
                    <h5>
                        {{
                            user_data.info_coordinador.length > 0
                                ? "Coordinador"
                                : user_data.info_estudiante.length > 0
                                ? "Estudiante"
                                : user_data.info_empresa.length > 0
                                ? "Empresa"
                                : "Administrador"
                        }}
                    </h5>
                </div>
                <div class="links">
                    <router-link to="/dashboard">Dashboard</router-link>
                    <router-link
                        to="/usuarios"
                        v-if="Number(user_data.id_tipo_usuario) === 1"
                        >Usuarios</router-link
                    >
                    <button type="button" @click="logout">Cerrar Sesión</button>
                </div>
            </div>
            <small>
                &copy; {{ new Date().getFullYear() }} Bolsa de oportunidades
            </small>
        </nav>
        <nav class="nav-mobile">
            <div>
                <button @click="toggleMenu" class="btn menu-button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
        <main class="py-4 px-5">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
import { api } from "../api";
import Alert from "../helpers/Alert";

export default {
    data() {
        return {
            loading: true,
            user_data: null,
            showMenu: false,
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
                this.user_data = response.data.data.user;
                this.loading = false;
            } catch (error) {
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                this.loading = false;
                this.$router.push("/login");
            }
        } else {
            this.loading = false;
            this.$router.push("/login");
        }
    },
    methods: {
        getPhotoAvata() {
            let nombre = "";

            if (this.user_data.info_coordinador.length > 0) {
                nombre =
                    this.user_data.info_coordinador[0].nombres +
                    " " +
                    this.user_data.info_coordinador[0].apellidos;
            } else if (this.user_data.info_estudiante.length > 0) {
                nombre =
                    this.user_data.info_estudiante[0].nombres +
                    " " +
                    this.user_data.info_estudiante[0].apellidos;
            } else if (this.user_data.info_empresa.length > 0) {
                return this.user_data.info_empresa[0].logo_url;
            } else {
                nombre = "Administrador";
            }

            return `https://ui-avatars.com/api/?name=${nombre}&background=random&color=fff`;
        },
        toggleMenu() {
            this.showMenu = !this.showMenu;
        },
        async logout() {
            this.loading = true;
            try {
                await api.post("/logout");
                localStorage.removeItem("token"); // Elimina los datos del usuario
                localStorage.removeItem("user_data"); // Elimina los datos del usuario
                this.$router.push("/"); // Redirige a la página de inicio
            } catch (error) {
                Alert("Error", error.response.data.message, "error");
                this.loading = false;
            }
        },
    },
};
</script>
