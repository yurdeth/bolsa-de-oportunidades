<template>
    <div class="loader" v-if="loading">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Estudiantes</h3>
        <div class="input-group mb-3 w-25">
            <input
                type="text"
                class="form-control"
                placeholder="Buscar estudiante"
                aria-label="Buscar estudiante"
                aria-describedby="button-addon2"
                ref="searchInput"
                @input="filterStudents"
            />
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" ref="studentsTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Carnet</th>
                    <th>Correo</th>
                    <th>Carrera</th>
                    <th class="text-center" style="width: 200px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="student in students" :key="student.id">
                    <td>{{ `${student.nombres} ${student.apellidos}` }}</td>
                    <td>{{ student.carnet }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.nombre_carrera }}</td>
                    <td class="text-center" style="width: 200px">
                        <div
                            class="d-flex justify-content-center gap-2 align-items-center flex-wrap"
                        >
                            <button
                                type="button"
                                class="btn btn-success btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop"
                                @click="viewStudent(student)"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                            <button
                                class="btn btn-danger btn-sm"
                                @click="confirmDelete(student.id)"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div
        class="modal fade"
        id="staticBackdrop"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Información del estudiante
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <img
                                    src="../../img/Logo_Nuevo.png"
                                    alt="Logo de la FMO"
                                    class="img-thumbnail"
                                />
                            </div>
                            <div class="col-6">
                                <p>
                                    <strong>Nombre completo:</strong>
                                    {{
                                        `${selectedStudent.nombres} ${selectedStudent.apellidos}`
                                    }}
                                </p>
                                <p>
                                    <strong>Correo institucional:</strong>
                                    {{ selectedStudent.email }}
                                </p>
                                <p>
                                    <strong>Carnet:</strong>
                                    {{ selectedStudent.carnet }}
                                </p>
                                <p>
                                    <strong>Carrera:</strong>
                                    {{ selectedStudent.nombre_carrera }}
                                </p>
                                <p>
                                    <strong>Año de estudio:</strong>
                                    {{ selectedStudent.anio_estudio }}°
                                </p>
                                <p>
                                    <strong>Dirección:</strong>
                                    {{ selectedStudent.direccion }}
                                </p>
                                <p>
                                    <strong>Teléfono:</strong>
                                    {{ selectedStudent.telefono }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { api } from "../../api";
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
                title: "¿Estás seguro?",
                text: "No podrás revertir esto",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminarlo",
                cancelButtonText: "Cancelar",
            });

            if (result.isConfirmed) {
                await this.deleteStudent(id);
                Swal.fire(
                    "Eliminado",
                    "El estudiante ha sido eliminado.",
                    "success"
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
            this.selectedStudent = { ...student };
        },
        filterStudents() {
            const searchTerm = this.$refs.searchInput.value.trim().toLowerCase();
            const table = this.$refs.studentsTable;

            if (!table) {
                console.error('Table reference is not defined');
                return;
            }

            const rows = table.getElementsByTagName('tr');

            Array.from(rows).forEach((row, index) => {
                if (index === 0) return; // Skip header row

                const cells = row.getElementsByTagName('td');
                let rowMatchesSearch = false;

                Array.from(cells).forEach(cell => {
                    const cellText = cell.textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        rowMatchesSearch = true;
                    }
                });

                row.style.display = rowMatchesSearch ? '' : 'none';
            });
        }
    },
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
