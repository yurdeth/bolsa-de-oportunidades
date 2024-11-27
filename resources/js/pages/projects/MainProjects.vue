<template>
    <div>
        <div class="loader" v-if="loading">
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Proyectos</h3>
            <button
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addCompanyModal"
            >
                Agregar Proyecto
            </button>
        </div>

        <div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                    <th>Modalidad</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <project-item
                    v-for="item in projects"
                    :key="item.id"
                    :project="item"
                    @delete-project="confirmDelete"
                    @view-project="viewProject"
                />
                </tbody>
            </table>
        </div>

        <!-- Ver Project Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Información del proyecto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <p><strong>Título:</strong> {{ selectedProject.titulo }}</p>

                                    <p><strong>Descripción:</strong> {{ selectedProject.descripcion }}</p>

                                    <div class="card-requisitos mb-3">
                                        <h5 class="fw-bold mb-3">Requisitos</h5>
                                        <ul class="p-0 m-0">
                                            <requisitos-item
                                                v-for="(requisito, index) in selectedProject.requisitos"
                                                :key="index"
                                                :valor="requisito">
                                            </requisitos-item>
                                        </ul>
                                    </div>

                                    <p><strong>Estado de Oferta:</strong>
                                        {{ selectedProject.estado_oferta_table?.nombre || 'No especificado' }}
                                    </p>

                                    <p><strong>Modalidad:</strong>
                                        {{ selectedProject.modalidad_trabajo_table?.nombre || 'No especificado' }}
                                    </p>

                                    <p><strong>Fecha Inicial:</strong>
                                        {{
                                            selectedProject.fecha_inicio ? new Date(selectedProject.fecha_inicio).toLocaleDateString() : 'No especificado'
                                        }}
                                    </p>

                                    <p><strong>Fecha Final:</strong>
                                        {{
                                            selectedProject.fecha_fin ? new Date(selectedProject.fecha_fin).toLocaleDateString() : 'No especificado'
                                        }}
                                    </p>

                                    <p><strong>Fecha Límite:</strong>
                                        {{
                                            selectedProject.fecha_limite_aplicacion ? new Date(selectedProject.fecha_limite_aplicacion).toLocaleDateString() : 'No especificado'
                                        }}
                                    </p>

                                    <p><strong>Estado:</strong>
                                        {{ selectedProject.estado_proyecto ? 'Activo' : 'Inactivo' }}
                                    </p>

                                    <p><strong>Cupos Disponibles:</strong>
                                        {{ selectedProject.cupos_disponibles || 0 }}
                                    </p>

                                    <p><strong>Tipo de Proyecto:</strong>
                                        {{ selectedProject.tipo_proyecto_table?.nombre || 'No especificado' }}
                                    </p>

                                    <p><strong>Ubicación:</strong>
                                        {{ selectedProject.ubicacion || 'No especificado' }}
                                    </p>

                                    <p><strong>Carrera:</strong>
                                        {{ selectedProject.carrera_table?.nombre_carrera || 'No especificado' }}
                                    </p>
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

        <!-- Agregar Empresa Modal -->
        <div class="modal fade" id="addCompanyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="addCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCompanyModalLabel">Agregar Nueva Empresa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addCompany">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Nombre Comercial</label>
                                    <input type="text" class="form-control" v-model="newProject.nombre" required>
                                </div>
                                <div class="card-requisitos">
                                    <h5 class="fw-bold mb-3">Requisitos</h5>
                                    <ul class="p-0 m-0">
                                        <requisitos-item v-for="(item, index) in newProject.nombre" :key="index"
                                                         :valor="item"></requisitos-item>
                                    </ul>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" v-model="newProject.email" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" v-model="newProject.telefono">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" class="form-control" v-model="newProject.direccion">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Sector Comercial</label>
                                    <input type="text" class="form-control" v-model="newProject.sector">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Sitio Web</label>
                                    <input type="url" class="form-control" v-model="newProject.sitio_web">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" v-model="newProject.descripcion"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Logo</label>
                                    <input type="file" class="form-control" @change="handleLogoUpload">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">Guardar Empresa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {api} from "../../api";
import ProjectItem from "@/pages/projects/ProjectItem.vue";
import Swal from "sweetalert2";
import RequisitosItem from "@/pages/projects/RequisitosItem.vue";

export default {
    components: {
        ProjectItem,
        RequisitosItem
    },
    data() {
        return {
            loading: false,
            proyecto: [],
            projects: [],
            selectedProject: {},
            newProject: {
                nombre: '',
                email: '',
                telefono: '',
                direccion: '',
                sector: '',
                sitio_web: '',
                descripcion: '',
                logo: null
            }
        };
    },
    async mounted() {
        this.loading = true;
        try {

            const user = JSON.parse(localStorage.getItem('user'));
            console.log(user.id_tipo_usuario);
            let response;
            let empresa;

            switch (user.id_tipo_usuario) {
                case 1:
                    response = await api.get("/proyectos");
                    this.projects = response.data.data;
                    break;
                case 2:
                    empresa = await api.get(`/proyectos`);

                    response = await api.get(`/proyectos`);
                    this.projects = response.data.data;
                    this.proyecto = response.data.data;
                    break;
                case 4:
                    const id_usuario = user.info_empresa[0].id;

                    empresa = await api.get(`/empresas/proyecto/${id_usuario}`);
                    console.log(empresa.data.data[0].id);

                    response = await api.get(`/proyectos/${empresa.data.data[0].id}`);

                    this.projects = response.data.data;
                    this.proyecto = response.data.data;
                    break;
            }

            this.projects = response.data.data;
            this.proyecto = response.data.data;
        } catch (error) {
            console.error(error);
        }
        this.loading = false;
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
                await this.deleteProject(id);
                Swal.fire(
                    'Eliminado',
                    'La empresa ha sido eliminado.',
                    'success'
                );
            }
        },
        async deleteProject(id) {
            try {
                await api.delete(`/proyectos/${id}`);
                this.proyecto = this.proyecto.filter(company => company.id !== id);
            } catch (error) {
                console.error(error);
            }
        },
        viewProject(project) {
            this.selectedProject = project;
        },
        handleLogoUpload(event) {
            this.newProject.logo = event.target.files[0];
        },

        dividirRequisitos(requisitos) {
            return requisitos.split(/,\s*/);
        },

        url(project) {
            return project.empresa_table.logo_url;
        },

        async addCompany() {
            this.loading = true;
            try {
                const formData = new FormData();
                Object.keys(this.newProject).forEach(key => {
                    formData.append(key, this.newProject[key]);
                });

                const response = await api.post("/proyectos", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.proyecto.push(response.data.data);

                // Reset form and close modal
                this.newProject = {
                    titulo: '',
                    descripcion: '',
                    requisitos: '',
                    id_estado_oferta: 0,
                    id_modalidad: 0,
                    fecha_inicio: null,
                    fecha_fin: null,
                    fecha_limite_aplicacion: null,
                    estado_proyecto: '',
                    cupos_disponibles: 1,
                    id_tipo_proyecto: 0,
                    ubicaion: '',
                    id_carrera: 0,
                };

                document.getElementById('addCompanyModal').querySelector('[data-bs-dismiss="modal"]').click();

                Swal.fire(
                    'Agregado',
                    'El proyecto ha sido agregado exitosamente.',
                    'success'
                );
            } catch (error) {
                console.error(error);
                Swal.fire(
                    'Error',
                    'No se pudo agregar el proyecto.',
                    'error'
                );
            }
            this.loading = false;
        }
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

.card-requisitos {
    border-radius: 10px;
    background-color: #f8f9fa;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
