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
            <tr v-for="coordinator in coordinators" :key="coordinator.id">
                <td>{{ `${coordinator.nombres} ${coordinator.apellidos}` }}</td>
                <td>{{ coordinator.email }}</td>
                <td>{{ coordinator.telefono }}</td>
                <td>{{ coordinator.nombre_departamento }}</td>
                <td>{{ coordinator.nombre_carrera }}</td>
                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop"
                        @click="viewCoordinator(coordinator)"
                    >
                        Editar
                    </button>
                    <button
                        class="btn btn-danger"
                        @click="deleteCoordinator(coordinator.id)"
                    >
                        Eliminar
                    </button>
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Información del coordinador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div v-if="selectedCoordinator">
                            <label for="nombre">Nombre: </label>
                            <input class="form-control" type="text" v-model="selectedCoordinator.nombres">
                        </div>

                        <div v-if="selectedCoordinator">
                            <label for="correo">Correo: </label>
                            <input class="form-control" type="text" v-model="selectedCoordinator.email">
                        </div>

                        <div v-if="selectedCoordinator">
                            <label for="telefono">Teléfono: </label>
                            <input class="form-control" type="text" v-model="selectedCoordinator.telefono">
                        </div>

                        <div v-if="selectedCoordinator">
                            <label for="departamento">Departamento: </label>

                            <div class="input-group">
                                <select
                                    id="id_departamwnto"
                                    class="form-control"
                                    name="id_departamento"
                                    v-model="selectedCoordinator.id_departamento"
                                    style="height: 40px"
                                    required
                                >
                                    <option value="" disabled>
                                        Seleccione un departamento
                                    </option>
                                    <option
                                        v-for="department in departments"
                                        :key="department.id"
                                        :value="department.id"
                                    >
                                        {{ department.nombre_departamento }}
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div v-if="selectedCoordinator">
                            <label for="departamento">Carrera: </label>

                            <div class="input-group">
                                <select
                                    id="id_carrera"
                                    class="form-control"
                                    name="id_carrera"
                                    v-model="selectedCoordinator.id_carrera"
                                    style="height: 40px"
                                    required
                                >
                                    <option value="" disabled>
                                        Seleccione una carrera
                                    </option>
                                    <option
                                        v-for="career in careers"
                                        :key="career.id"
                                        :value="career.id"
                                    >
                                        {{ career.nombre_carrera }}
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="row p-3">
                            <div class="col-8"></div>
                            <div class="col-4">
                                <button class="btn btn-success">Actualizar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            </div>
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
            loading: false,
            coordinators: [],
            departments: [],
            careers: [],
            selectedCoordinator: {},
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

        await this.loadDepartments();
    },
    watch: {
        'selectedCoordinator.id_departamento': function(newVal) {
            if (newVal) {
                this.loadCareers();
            }
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
        viewCoordinator(coordinator) {
            this.selectedCoordinator = coordinator;
        },
        async loadDepartments() {
            try {
                const response = await api.get("/departamentos");
                this.departments = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },
        async loadCareers() {
            try {
                const id = this.selectedCoordinator.id_departamento;
                const response = await api.get(`/departamentos/carreras/${id}`);
                this.careers = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>
