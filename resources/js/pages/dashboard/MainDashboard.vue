<template>
    <div
        class="loader"
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
    <div v-if="!loading">
        <h1>Bienvenido a Dashboard</h1>
        <router-link to="/">Ir a Home</router-link>
    </div>
</template>

<script>
import { api } from "../../api";

export default {
    data() {
        return {
            loading: true,
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
};
</script>
<style>
.loader {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
