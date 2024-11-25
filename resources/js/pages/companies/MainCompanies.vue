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
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Sector comercial</th>
                <th>Sitio web</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="company in companies" :key="company.id">
                <td>{{ company.nombre }}</td>
                <td>{{ company.email }}</td>
                <td>{{ company.sector }}</td>
                <td>{{ company.sitio_web }}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop" @click="viewCompany(company)">Ver
                    </button>
                    <button class="btn btn-danger" @click="deleteCompany(company.id)">Eliminar</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Información de la empresa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <img :src="selectedCompany.logo_url"
                                     alt="Logo de la empresa" class="img-thumbnail">
                            </div>
                            <div class="col-6">
                                <p><strong>Nombre comercial:</strong> {{ selectedCompany.nombre }}</p>
                                <p><strong>Correo:</strong> {{ selectedCompany.email }}</p>
                                <p><strong>Teléfono:</strong> {{ selectedCompany.telefono }}</p>
                                <p><strong>Ubicación:</strong> {{ selectedCompany.direccion }}</p>
                                <p><strong>Sector comercial:</strong> {{ selectedCompany.sector }}</p>
                                <p><strong>Sitio web:</strong> <a href="{{ selectedCompany.sitio_web }}"
                                                                  target="_blank">{{ selectedCompany.sitio_web }}</a>
                                </p>
                                <p><strong>Descripción:</strong> {{ selectedCompany.descripcion }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {api} from "../../api";

export default {
    data() {
        return {
            loading: false,
            companies: [],
            selectedCompany: {}
        };
    },
    async mounted() {
        this.loading = true;

        try {
            const response = await api.get("/empresas");
            // console.log(response.data.data);
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
        },
        viewCompany(company) {
            this.selectedCompany = company;
        }
    }
};
</script>
