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
                        @click="viewStudent(student)"
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
                                     alt="Logo de la FMO" class="img-thumbnail">
                            </div>
                            <div class="col-6">
                                <p><strong>Nombre completo:</strong>
                                    {{ `${selectedStudent.nombres} ${selectedStudent.apellidos}` }} </p>
                                <p><strong>Correo institucional:</strong> {{ selectedStudent.email }} </p>
                                <p><strong>Carnet:</strong> {{ selectedStudent.carnet }} </p>
                                <p><strong>Carrera:</strong> {{ selectedStudent.nombre_carrera }} </p>
                                <p><strong>Año de estudio:</strong> {{ selectedStudent.anio_estudio }}° </p>
                                <p><strong>Dirección:</strong> {{ selectedStudent.direccion }} </p>
                                <p><strong>Teléfono:</strong> {{ selectedStudent.telefono }} </p>
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
            selectedStudent: {},
        };
    },
    async mounted() {
        this.loading = true;
        try {
            const response = await api.get("/estudiantes");
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
                await this.deleteStudent(id);
                Swal.fire(
                    'Eliminado',
                    'El estudiante ha sido eliminado.',
                    'success'
                );
            }
        },
        async deleteStudent(id) {
            try {
                await api.delete(`/estudiantes/${id}`);
                this.students = this.students.filter(
                    (student) => student.id !== id
                );
            } catch (error) {
                console.error(error);
            }
        },
        viewStudent(student) {
            this.selectedStudent = {...student};
        },
    }
};
</script>

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
