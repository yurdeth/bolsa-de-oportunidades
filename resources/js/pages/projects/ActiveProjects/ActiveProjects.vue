<script setup>
import {api} from "@/api.js";
import Swal from "sweetalert2";
import {onMounted, ref} from "vue";

const loading = ref(false);
const id_tipo_usuario = JSON.parse(localStorage.getItem("user")).id_tipo_usuario;

const data = ref({
    projects: [],
});

const searchInput = ref(null);

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

const filterProjects = () => {
    const searchTerm = searchInput.value.value.trim().toLowerCase();
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
        const projectTitle = item.querySelector('.accordion-button').textContent.toLowerCase();
        const projectDescription = item.querySelector('.accordion-body').textContent.toLowerCase();

        if (projectTitle.includes(searchTerm) || projectDescription.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
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
                ref="searchInput"
                @input="filterProjects"
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
                        </div>
                    </div>
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
