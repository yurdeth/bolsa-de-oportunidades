<template>
    <div class="loader" v-if="loading">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-10">
                <h3>Coordinadores</h3>
            </div>
            <div class="col-2">
                <router-link to="#" class="btn btn-primary float-right">
                    Nuevo Coordinador
                </router-link>
            </div>
        </div>
    </div>

    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Departamento</th>
                <th>Carrera</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <coordinator-item
                v-for="coordinator in coordinators"
                :key="coordinator.id"
                :coordinator="coordinator"
                @delete-coordinator="deleteCoordinator"
                @edit-coordinator="editCoordinator"
                @view-coordinator="viewCoordinator"
            />
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Información del coordinador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ console.log(selectedCoordinator) }}
                    <p v-if="selectedCoordinator"><strong>Nombre:</strong>
                        {{ `${selectedCoordinator.nombres} ${selectedCoordinator.apellidos}` }}</p>
                    <p v-if="selectedCoordinator"><strong>Correo:</strong> {{ selectedCoordinator.email }}</p>
                    <p v-if="selectedCoordinator"><strong>Teléfono:</strong> {{ selectedCoordinator.telefono }}</p>
                    <p v-if="selectedCoordinator"><strong>Departamento:</strong>
                        {{ selectedCoordinator.nombre_departamento }}</p>
                    <p v-if="selectedCoordinator"><strong>Carrera:</strong> {{ selectedCoordinator.nombre_carrera }}</p>
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
import CoordinatorItem from './CoordinatorItem.vue';

export default {
    components: {
        CoordinatorItem,
    },
    data() {
        return {
            loading: false,
            coordinators: [],
            selectedCoordinator: {}, // Initialize as an empty object
        };
    },
    async mounted() {
        this.loading = true;
        try {
            const response = await api.get("/coordinadores");
            this.coordinators = response.data.data;
        } catch (error) {
            console.error(error);
        } finally {
            this.loading = false;
        }
    },
    methods: {
        async deleteCoordinator(id) {
            try {
                await api.delete(`/coordinadores/${id}`);
                this.coordinators = this.coordinators.filter(
                    (coordinator) => coordinator.id !== id
                );
            } catch (error) {
                console.error(error);
            }
        },
        editCoordinator(coordinator) {
            this.selectedCoordinator = coordinator;
        },
        viewCoordinator(coordinator) {
            this.selectedCoordinator = coordinator;
        },
    },
};
</script>
