<style scoped>
.loader {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.loader .spinner-border {
    width: 100px;
    height: 100px;
}
</style>

<template>
    <div class="loader" v-if="loading">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <h3>Compañías</h3>

    <div>
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Sector comercial</th>
                <th>Sitio web</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="company in companies" :key="company.id">
                <td>{{ company.id }}</td>
                <td>{{ company.nombre }}</td>
                <td>{{ company.email }}</td>
                <td>{{ company.direccion }}</td>
                <td>{{ company.telefono }}</td>
                <td>{{ company.sector }}</td>
                <td>{{ company.sitio_web }}</td>
                <td>
                    <!--                        <router-link :to="{ name: 'company', params: { id: company.id } }">
                                                <button class="btn btn-primary">Ver</button>
                                            </router-link>-->
                    <!--                        <button class="btn btn-success">Ver</button>-->
                    <button class="btn btn-danger" @click="deleteCompany(company.id)">Eliminar</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import {api} from "../../api";

export default {

    data() {
        return {
            loading: false,
        };
    },
    async mounted() {
        this.loading = true;

        try {
            const response = await api.get("/empresas");
            this.companies = response.data.data;
        } catch (error) {
            console.error(error);
        }
        this.loading = false;
    },
    methods: {
        async deleteCompany(id) {
            try {
                await api.delete(`/empresas/${id}`);
                this.companies = this.companies.filter(company => company.id !== id);
            } catch (error) {
                console.error(error);
            }
        }
    }
};
</script>
