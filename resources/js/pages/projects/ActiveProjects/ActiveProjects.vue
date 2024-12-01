<script setup>
import { api } from "@/api.js";
import Swal from "sweetalert2";
import { onMounted, ref } from "vue";

const loading = ref(false);
const id_tipo_usuario = JSON.parse(
    localStorage.getItem("user")
).id_tipo_usuario;

const data = ref({
    projects: [],
});

const selectedProject = ref(null);
const selectedStudent = ref(null);

const getActiveProjects = async () => {
    loading.value = true;
    try {
        const response = await api.get("/proyectos-activos");
        const projects = response.data.data;

        // Group projects by id_proyecto and aggregate students
        const groupedProjects = projects.reduce((acc, project) => {
            if (!acc[project.id_proyecto]) {
                acc[project.id_proyecto] = {
                    ...project,
                    estudiantes: [],
                };
            }
            acc[project.id_proyecto].estudiantes.push({
                id_estudiante: project.id_estudiante,
                nombres: project.nombres,
                apellidos: project.apellidos,
                email: project.email,
            });
            return acc;
        }, {});

        data.value.projects = Object.values(groupedProjects);
        console.log(data.value.projects);
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const retirarEstudiante = async () => {
    try {
        let url = "";

        if (id_tipo_usuario === 2) {
            url = "/expulsar-estudiante";
        } else {
            url = "/retirar-estudiante";
        }

        Swal.fire({
            title: "¿Estás seguro?",
            text: "El estudiante será retirado del proyecto",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, retirar",
            cancelButtonText: "Cancelar",
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await api.post(url, {
                    id_estudiante: selectedStudent.value,
                    id_proyecto: selectedProject.value.id_proyecto,
                });

                if (response.data.success) {
                    Swal.fire({
                        title: "Éxito",
                        text: response.data.message,
                        icon: "success",
                        confirmButtonText: "Aceptar",
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.data.message,
                        icon: "error",
                        confirmButtonText: "Aceptar",
                    }).then(() => {
                        window.location.reload();
                    });
                }
            } else {
                Swal.fire({
                    title: "Operación cancelada",
                    text: "El estudiante no fue retirado del proyecto",
                    icon: "success",
                    confirmButtonText: "Aceptar",
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    } catch (error) {
        console.error(error);
    }
};

const openModal = (project) => {
    selectedProject.value = project;
    selectedStudent.value = null;
};

onMounted(() => {
    getActiveProjects();
});
</script>

<template>
    <div class="loader" v-if="loading">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Proyectos activos</h3>
        <div class="input-group mb-3 w-25">
            <input
                type="text"
                class="form-control"
                placeholder="Buscar proyecto"
                aria-label="Buscar proyecto"
                aria-describedby="button-addon2"
            />
        </div>
    </div>

    <div>
        <div class="accordion" id="accordionExample">
            <div
                class="accordion-item"
                v-for="project in data.projects"
                :key="project.id_proyecto"
            >
                <h2 class="accordion-header">
                    <button
                        class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        :data-bs-target="'#collapse' + project.id_proyecto"
                        aria-expanded="true"
                        :aria-controls="'collapse' + project.id_proyecto"
                    >
                        {{ project.titulo }}
                    </button>
                </h2>
                <div
                    :id="'collapse' + project.id_proyecto"
                    class="accordion-collapse collapse show"
                    data-bs-parent="#accordionExample"
                >
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-12">
                                <h5>Descripción</h5>
                                <p>{{ project.descripcion }}</p>
                            </div>
                            <div class="col-12">
                                <h5>Empresa</h5>
                                <p>{{ project.empresa }}</p>
                            </div>
                            <div class="col-12">
                                <h5>Estudiantes asignados al proyecto</h5>
                                <ul>
                                    <li
                                        v-for="estudiante in project.estudiantes"
                                        :key="estudiante.id_estudiante"
                                    >
                                        {{
                                            estudiante.nombres +
                                            " " +
                                            estudiante.apellidos
                                        }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <h5>Modalidad</h5>
                                <p>{{ project.modalidad }}</p>
                            </div>
                            <div class="col-12">
                                <h5>Tipo de proyecto</h5>
                                <p>{{ project.tipo_proyecto }}</p>
                            </div>
                            <!--                            A discusion, esta opcion no se implementará, pues eso sería tema de seguimiento-->
                            <!--                            <div class="col-12" v-if="id_tipo_usuario === 2">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#retirarEstudiante" @click="openModal(project)">
                                    Retirar un estudiante del proyecto
                                </button>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Modal para seleccionar el estudiante a retirar-->
        <div
            class="modal fade"
            id="retirarEstudiante"
            data-bs-backdrop="static"
            data-bs-keyboard="false"
            tabindex="-1"
            aria-labelledby="staticBackdropLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            Seleccionar el estudiante que deseas retirar del
                            proyecto
                        </h1>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body" v-if="selectedProject">
                        <div class="form-group">
                            <label for="estudiantes">Estudiantes</label>
                            <select
                                class="form-select"
                                id="estudiantes"
                                v-model="selectedStudent"
                            >
                                <option selected disabled>
                                    Selecciona un estudiante
                                </option>
                                <option
                                    v-for="estudiante in selectedProject.estudiantes"
                                    :key="estudiante.id_estudiante"
                                    :value="estudiante.id_estudiante"
                                >
                                    {{
                                        estudiante.nombres +
                                        " " +
                                        estudiante.apellidos
                                    }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <!--                    A discusion, esta opcion no se implementará, pues eso sería tema de seguimiento-->
                    <!--                    <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-danger" @click="retirarEstudiante">
                                                Retirar del proyecto
                                            </button>
                                        </div>-->
                </div>
            </div>
        </div>
    </div>
</template>

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
