<template>
    <div class="loader" v-if="loading">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-12">
                <h3>Estudiantes</h3>
            </div>
        </div>
    </div>

    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Carnet</th>
                <th>Correo</th>
                <th>Carrera</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="student in students" :key="student.id">
                <td>{{ `${student.nombres} ${student.apellidos}` }}</td>
                <td>{{ student.carnet }}</td>
                <td>{{ student.email }}</td>
                <td>{{ student.nombre_carrera }}</td>
                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop"
                        @click="viewCoordinator(student)"
                    >
                        Ver
                    </button>
                    <button
                        class="btn btn-danger"
                        @click="confirmDelete(student.id)"
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Información del estudiante</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <img src="../../img/Logo_Nuevo.png"
                                     alt="Logo de la empresa" class="img-thumbnail">
                            </div>
                            <div class="col-6">
                                <p><strong>Nombre completo:</strong> </p>
                                <p><strong>Correo institucional:</strong> </p>
                                <p><strong>Carnet:</strong> </p>
                                <p><strong>Carrera:</strong> </p>
                                <p><strong>Año de estudio:</strong> </p>
                                <p><strong>Dirección:</strong> </p>
                                <p><strong>Teléfono:</strong> </p>
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
import Swal from "sweetalert2";

export default {
    data() {
        return {
            loading: false,
            students: [],
            selectedCoordinator: {},
        };
    },
    async mounted() {
        this.loading = true;
        try {
            const response = await api.get("/estudiantes");
            console.log(response.data.data);
            this.students = response.data.data;
        } catch (error) {
            console.error(error);
        } finally {
            this.loading = false;
        }
    },
    methods: {
        async confirmDelete(id) {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                await this.deleteCoordinator(id);
                Swal.fire(
                    'Eliminado',
                    'El coordinador ha sido eliminado.',
                    'success'
                );
            }
        },
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
            this.form = {...coordinator};
            this.createNew = false;
        },
    }
};
</script>
