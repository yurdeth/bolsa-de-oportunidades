<template>
    <div>
        <div class="loader" v-if="loading">
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Proyectos publicados para mi carrera</h3>
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
            <router-link
                class="btn btn-primary"
                to="/proyectos">
                Regresar a proyectos
            </router-link>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" ref="projectsTable">
                <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                    <th>Modalidad</th>
                    <th class="text-center" style="width: 220px">
                        Acciones
                    </th>
                </tr>
                </thead>
                <tbody>
                <project-item
                    v-for="item in projects"
                    :key="item.id"
                    :project="item"
                    :is-tipo-usuario-2="isTipoUsuario2"
                    :idTipoUsuario="idTipoUsuario"
                    @delete-project="confirmDelete"
                    @view-project="viewProject"
                    @edit-project="editProject"
                />
                </tbody>
            </table>
        </div>

        <!-- Ver Project Modal -->
        <div
            class="modal fade"
            id="staticBackdrop"
            data-bs-backdrop="static"
            data-bs-keyboard="false"
            tabindex="-1"
            aria-labelledby="staticBackdropLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">
                            Información del proyecto
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
                                <div class="col-12">
                                    <p>
                                        <strong>Título:</strong>
                                        {{
                                            selectedProject.titulo ? selectedProject.titulo : selectedProject.titulo_proyecto
                                        }}
                                    </p>

                                    <p>
                                        <strong>Descripción:</strong>
                                        {{
                                            selectedProject.descripcion ? selectedProject.descripcion : selectedProject.descripcion_proyeto
                                        }}
                                    </p>

                                    <div class="card-requisitos mb-3">
                                        <h5 class="fw-bold mb-3">Requisitos</h5>
                                        <ul class="p-0 m-0">
                                            <requisitos-item
                                                v-for="(requisito, index) in requisitosArray"
                                                :key="index"
                                                :valor="requisito"
                                            >
                                            </requisitos-item>
                                        </ul>
                                    </div>

                                    <p>
                                        <strong>Estado de Oferta:</strong>
                                        {{
                                            selectedProject.estado_oferta_table?.nombre_estado || selectedProject.estado_oferta
                                        }}
                                    </p>

                                    <p>
                                        <strong>Modalidad:</strong>
                                        {{
                                            selectedProject.modalidad_trabajo_table?.nombre || selectedProject.modalidad
                                        }}
                                    </p>

                                    <p>
                                        <strong>Fecha Inicial:</strong>
                                        {{
                                            selectedProject.fecha_inicio
                                                ? new Date(
                                                    selectedProject.fecha_inicio
                                                ).toLocaleDateString()
                                                : new Date(
                                                    selectedProject.fecha_inicio_proyecto
                                                ).toLocaleDateString()
                                        }}
                                    </p>

                                    <p>
                                        <strong>Fecha Final:</strong>
                                        {{
                                            selectedProject.fecha_fin
                                                ? new Date(
                                                    selectedProject.fecha_fin
                                                ).toLocaleDateString()
                                                : new Date(
                                                    selectedProject.fecha_fin_proyecto
                                                ).toLocaleDateString()
                                        }}
                                    </p>

                                    <p>
                                        <strong>Fecha Límite:</strong>
                                        {{
                                            selectedProject.fecha_limite_aplicacion
                                                ? new Date(
                                                    selectedProject.fecha_limite_aplicacion
                                                ).toLocaleDateString()
                                                : "No especificado"
                                        }}
                                    </p>

                                    <p>
                                        <strong>Estado:</strong>
                                        {{
                                            selectedProject.estado_proyecto
                                                ? "Activo"
                                                : "Inactivo"
                                        }}
                                    </p>

                                    <p>
                                        <strong>Cupos Disponibles:</strong>
                                        {{
                                            selectedProject.cupos_disponibles ||
                                            0
                                        }}
                                    </p>

                                    <p>
                                        <strong>Tipo de Proyecto:</strong>
                                        {{
                                            selectedProject.tipo_proyecto_table
                                                ?.nombre || selectedProject.tipo_proyecto
                                        }}
                                    </p>

                                    <p>
                                        <strong>Ubicación:</strong>
                                        {{
                                            selectedProject.ubicacion || selectedProject.ubicacion_proyecto
                                        }}
                                    </p>

                                    <p>
                                        <strong>Carrera:</strong>
                                        {{
                                            selectedProject.carrera_table
                                                ?.nombre_carrera || selectedProject.nombre_carrera
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-target="#staticBackdrop"
                            data-bs-dismiss="modal"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {api} from "@/api.js";
import ProjectItem from "@/pages/projects/MainProjects/ProjectItem.vue";
import Swal from "sweetalert2";
import RequisitosItem from "@/pages/projects/MainProjects/RequisitosItem.vue";

export default {
    components: {
        ProjectItem,
        RequisitosItem,
    },
    computed: {
        isTipoUsuario2() {
            const user = JSON.parse(localStorage.getItem("user"));
            return user.id_tipo_usuario === 2;
        },

        idTipoUsuario() {
            const user = JSON.parse(localStorage.getItem("user"));
            return user.id_tipo_usuario;
        },
        requisitosArray() {
            const requisitos = this.selectedProject.requisitos || this.selectedProject.requisitos_proyecto;
            if (Array.isArray(requisitos)) {
                return requisitos;
            } else if (typeof requisitos === 'string') {
                return requisitos.split(',');
            } else {
                return [];
            }
        }
    },
    data() {
        return {
            loading: false,
            proyecto: [],
            projects: [],
            selectedProject: {},
            interested: [],
            currentRequirement: "",
            estados_oferta: [],
            modalidades: [],
            tipos_proyecto: [],
            empresa_id: "",
            carreras: [],
            newProject: {
                titulo: "",
                descripcion: "",
                requisitos: [],
                id_estado_oferta: "",
                id_modalidad: "",
                fecha_inicio: null,
                fecha_fin: null,
                fecha_limite_aplicacion: null,
                estado_proyecto: "",
                cupos_disponibles: 1,
                id_tipo_proyecto: "",
                ubicacion: "",
                id_carrera: "",
                id_empresa: "",
            },
            info_estudiante_interesado: {
                id_estudiante: "",
                id_proyecto: "",
                aprobado: true,
            },
            id_tipo_usuario: JSON.parse(localStorage.getItem("user"))
                .id_tipo_usuario,
            editForm: {
                id: null,
                titulo: "",
                descripcion: "",
                requisitos: [],
                id_estado_oferta: "",
                id_modalidad: "",
                id_tipo_proyecto: "",
                id_carrera: "",
                cupos_disponibles: 1,
            },
        };
    },
    async mounted() {
        this.loading = true;
        try {
            const user = JSON.parse(localStorage.getItem("user"));
            let response;
            let empresa;
            empresa = await api.get(`/proyectos/publicados`);

            response = await api.get(`/proyectos/publicados`);
            this.projects = response.data.data;
            this.proyecto = response.data.data;
        } catch (error) {
            console.error(error);
        }
        this.loading = false;
    },
    methods: {
        viewProject(project) {
            console.log(project);
            this.selectedProject = project;
        },
        editProject(project) {
            this.editForm = {
                id: project.id,
                titulo: project.titulo,
                descripcion: project.descripcion,
                requisitos: Array.isArray(project.requisitos)
                    ? [...project.requisitos]
                    : project.requisitos.split(','),
                id_estado_oferta: project.id_estado_oferta,
                id_modalidad: project.id_modalidad,
                id_tipo_proyecto: project.id_tipo_proyecto,
                id_carrera: project.id_carrera,
                cupos_disponibles: project.cupos_disponibles || 1,
            };
            const modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
            modal.show();
        },
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
                await this.deleteProject(id);
                Swal.fire(
                    "Eliminado",
                    "La empresa ha sido eliminado.",
                    "success"
                ).then(() => {
                    window.location.reload();
                });
            }
        },
        async deleteProject(id) {
            try {
                await api.delete(`/proyectos/${id}`);
                this.proyecto = this.proyecto.filter(
                    (company) => company.id !== id
                );
            } catch (error) {
                console.error(error);
            }
        },
        url(project) {
            return project.empresa_table.logo_url;
        },
        //---------------------------
        filterProjects() {
            const searchTerm = this.$refs.searchInput.value.trim().toLowerCase();
            const table = this.$refs.projectsTable;

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

.card-requisitos {
    border-radius: 10px;
    background-color: #f8f9fa;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
