<template>
    <div>
        <h1>Bienvenido a Dashboard</h1>
        <router-link to="/">Ir a Home</router-link>
    </div>
</template>

<script>
import { api } from "../../api";

export default {
    data() {
        return {
            loading: false,
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
