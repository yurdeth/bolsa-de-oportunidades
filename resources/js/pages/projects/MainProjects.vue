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
                data-bs-target="#addProjectModal"
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
                    :is-tipo-usuario-2="isTipoUsuario2"
                    :idTipoUsuario="idTipoUsuario"
                    @delete-project="confirmDelete"
                    @view-project="viewProject"
                    @click="viewProject(item)"
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

        <!-- Agregar Proyecto Modal -->
        <div class="modal fade" id="addProjectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="addProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addProjectModalLabel">Agregar Nuevo Proyecto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addProject">
                            <div class="row">
                                <!-- Estado -->
                                <div class="col-6 mb-3">
                                    <label class="form-label">Estado de Oferta</label>
                                    <select class="form-select" v-model="newProject.id_estado_oferta" required>
                                        <option value="">Seleccionar Estado</option>
                                        <option v-for="estado in estados_oferta" :key="estado.id" :value="estado.id" >{{ estado.nombre_estado }}</option>
                                    </select>
                                </div>

                                <!-- Modalidad -->
                                <div class="col-6 mb-3">
                                    <label class="form-label">Modalidad de Trabajo</label>
                                    <select class="form-select" v-model="newProject.id_modalidad" required>
                                        <option value="">Seleccionar Modalidad</option>
                                        <option v-for="modalidad in modalidades" :key="modalidad.id" :value="modalidad.id" >{{ modalidad.nombre }}</option>
                                    </select>
                                </div>

                                <!-- Título -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Título del Proyecto</label>
                                    <input type="text" class="form-control" v-model="newProject.titulo" required>
                                </div>

                                <!-- Descripción -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Descripción del Proyecto</label>
                                    <textarea class="form-control" v-model="newProject.descripcion" required></textarea>
                                </div>

                                <!-- Requisitos -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Requisitos</label>
                                    <div class="input-group mb-3">
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="currentRequirement"
                                            @keyup.enter="addRequirement"
                                            placeholder="Escribe un requisito y presiona Ctrl + Enter o haz click en Agregar"
                                        >
                                        <button class="btn btn-outline-secondary" type="button" @click="addRequirement">
                                            Agregar
                                        </button>
                                    </div>
                                    <div v-if="newProject.requisitos && newProject.requisitos.length" class="mb-2">
                                        <span
                                            v-for="(req, index) in newProject.requisitos"
                                            :key="index"
                                            class="badge bg-secondary me-2 mb-1"
                                        >
                                            {{ req }}
                                            <button
                                                type="button"
                                                class="btn-close btn-close-white"
                                                aria-label="Eliminar"
                                                @click="removeRequirement(index)"
                                            ></button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Tipo de Proyecto</label>
                                    <select class="form-select" v-model="newProject.id_tipo_proyecto" required>
                                        <option value="">Seleccionar Tipo</option>
                                        <option v-for="tipo in tipos_proyecto" :key="tipo.id" :value="tipo.id" >{{ tipo.nombre }}</option>
                                    </select>
                                </div>

                                <!-- Carrera -->
                                <div class="col-6 mb-3">
                                    <label class="form-label">Carrera</label>
                                    <select class="form-select" v-model="newProject.id_carrera" required>
                                        <option value="">Seleccionar Carrera</option>
                                        <option v-for="carrera in carreras" :key="carrera.id" :value="carrera.id" >{{ carrera.nombre_carrera }}</option>
                                    </select>
                                </div>

                                <!-- Fechas -->
                                <div class="col-4 mb-3">
                                    <label class="form-label">Fecha de Inicio</label>
                                    <input type="date" class="form-control" v-model="newProject.fecha_inicio" required>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label">Fecha de Fin</label>
                                    <input type="date" class="form-control" v-model="newProject.fecha_fin" required>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label">Fecha Límite de Aplicación (Opcional)</label>
                                    <input type="date" class="form-control" v-model="newProject.fecha_limite_aplicacion">
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Estado de Proyecto</label>
                                    <select class="form-select" v-model="newProject.estado_proyecto" required>
                                        <option value="">Seleccionar Estado</option>
                                        <option :value="1">Activo</option>
                                        <option :value="0">Inactivo</option>
                                    </select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Cupos Disponibles</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        v-model.number="newProject.cupos_disponibles"
                                        min="1"
                                        required
                                    >
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Ubicación</label>
                                    <input type="text" class="form-control" v-model="newProject.ubicacion">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar Proyecto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar lista de estudiantes interesados en el proyecto -->
        <!-- Mostrar lista de estudiantes interesados en el proyecto -->
        <div class="modal fade" id="staticInterested" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="interestedLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="interestedLabel">Estudiantes interesados</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Carnet</th>
                                <th>Email</th>
                                <th>Carrera</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="student in interested" :key="student.carnet">
                                <td>{{ student.nombres }}</td>
                                <td>{{ student.apellidos }}</td>
                                <td>{{ student.carnet }}</td>
                                <td>{{ student.email }}</td>
                                <td>{{ student.nombre_carrera }}</td>
                                <td>{{ student.telefono }}</td>
                                <td>{{ student.direccion }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { api } from "@/api.js";
import ProjectItem from "@/pages/projects/ProjectItem.vue";
import Swal from "sweetalert2";
import RequisitosItem from "@/pages/projects/RequisitosItem.vue";

export default {
    components: {
        ProjectItem,
        RequisitosItem
    },
    computed: {
        isTipoUsuario2() {
            const user = JSON.parse(localStorage.getItem('user'));
            return user.id_tipo_usuario === 2;
        },

        idTipoUsuario() {
            const user = JSON.parse(localStorage.getItem('user'));
            return user.id_tipo_usuario;
        }
    },
    data() {
        return {
            loading: false,
            proyecto: [],
            projects: [],
            selectedProject: {},
            interested: [],
            currentRequirement: '',
            estados_oferta: [],
            modalidades: [],
            tipos_proyecto: [],
            empresa_id: '',
            carreras: [],
            newProject: {
                titulo: '',
                descripcion: '',
                requisitos: [],
                id_estado_oferta: '',
                id_modalidad: '',
                fecha_inicio: null,
                fecha_fin: null,
                fecha_limite_aplicacion: null,
                estado_proyecto: '',
                cupos_disponibles: 1,
                id_tipo_proyecto: '',
                ubicacion: '',
                id_carrera: '',
                id_empresa: ''
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

                    response = await api.get(`/proyectos/empresa/${empresa.data.data[0].id}`);
                    this.empresa_id = empresa.data.data[0].id;

                    this.projects = response.data.data;
                    this.proyecto = response.data.data;
                    break;
            }

            this.projects = response.data.data;
            this.proyecto = response.data.data;

            await this.cargarEstadosOferta();
            await this.cargarModalidades();
            await this.cargarTiposProyecto();
            await this.cargarCarreras();
        } catch (error) {
            console.error(error);
        }
        this.loading = false;
    },
    methods: {

        redirectToRoute() {
            this.$router.push({ name: '/proyectos' });
        },

        async cargarEstadosOferta() {
            try {
                const response = await api.get("/estado-oferta");
                this.estados_oferta = response.data.data;
                console.log(this.estados_oferta);
            } catch (error) {
                console.error(error);
            }
        },

        async cargarModalidades() {
            try {
                const response = await api.get("/modalidades-trabajo");
                this.modalidades = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },

        async cargarTiposProyecto() {
            try {
                const response = await api.get("/tipos-proyecto");
                this.tipos_proyecto = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },

        async cargarCarreras() {
            try {
                const response = await api.get("/carreras");
                this.carreras = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },

        async addProject() {
            this.loading = true;
            try {

                Object.keys(this.newProject).forEach(key => {
                    if (key === 'requisitos') {
                        this.newProject[key] = this.newProject[key].join(',');
                    }
                });

                console.log("Empresa ID: " + this.empresa_id);
                this.newProject.id_empresa = this.empresa_id;
                console.log("Id de empresa" + this.newProject.id_empresa);
                console.log("Titulo: " + this.newProject.titulo);
                console.log("Descripcion: " + this.newProject.descripcion);
                console.log("Requisitos: " + this.newProject.requisitos);
                console.log("Estado de oferta: " + this.newProject.id_estado_oferta);
                console.log("Modalidad: " + this.newProject.id_modalidad);
                console.log("Fecha de inicio: " + this.newProject.fecha_inicio);
                console.log("Fecha de fin: " + this.newProject.fecha_fin);
                console.log("Fecha limite de aplicacion: " + this.newProject.fecha_limite_aplicacion);
                console.log("Estado de proyecto: " + this.newProject.estado_proyecto);
                console.log("Cupos disponibles: " + this.newProject.cupos_disponibles);
                console.log("Tipo de proyecto: " + this.newProject.id_tipo_proyecto);
                console.log("Ubicacion: " + this.newProject.ubicacion);
                console.log("Carrera: " + this.newProject.id_carrera);

                const response = await api.post("/proyectos", this.newProject);
                //Redireccionar a la vista de proyectos

                this.projects.push(response.data.data);

                // Reset form and close modal
                this.newProject = {
                    titulo: '',
                    descripcion: '',
                    requisitos: [],
                    id_estado_oferta: '',
                    id_modalidad: '',
                    fecha_inicio: null,
                    fecha_fin: null,
                    fecha_limite_aplicacion: null,
                    estado_proyecto: '',
                    cupos_disponibles: 1,
                    id_tipo_proyecto: '',
                    ubicacion: '',
                    id_carrera: '',
                };
                this.currentRequirement = ''; // Reset current requirement input

                document.getElementById('addProjectModal').querySelector('[data-bs-dismiss="modal"]').click();

                Swal.fire(
                    'Agregado',
                    'El proyecto ha sido agregado exitosamente.',
                    'success'
                ).then(() => {
                    window.location.reload();
                });
                // return this.$router.push({ name: 'proyectos' });
            } catch (error) {
                console.error(error);
                Swal.fire(
                    'Error',
                    'No se pudo agregar el proyecto.' + error,
                    'error'
                );
            }
            this.newProject.requisitos = this.newProject.requisitos.split(',');
            this.loading = false;
        },

        // Method to add requirements dynamically
        addRequirement() {
            if (this.currentRequirement && this.currentRequirement.trim() !== '') {
                if (!this.newProject.requisitos) {
                    this.newProject.requisitos = [];
                }
                this.newProject.requisitos.push(this.currentRequirement.trim());
                this.currentRequirement = ''; // Clear input after adding
            }
        },

        // Method to remove a requirement
        removeRequirement(index) {
            this.newProject.requisitos.splice(index, 1);
        },
        viewProject(project) {
            this.selectedProject = project;
        },
        async loadInteresteds() {
            try {
                console.log("ID Proyecto: " + this.selectedProject.id);
                const response = await api.get(`/proyectos/interesados/${this.selectedProject.id}`);
                console.log(response.data.data);
                this.interested = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },
        viewInterested() {
            this.loadInteresteds();
            const modal = new bootstrap.Modal(document.getElementById('staticInterested'));
            modal.show();
        },
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
                ).then(() => {
                    window.location.reload();
                });
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
                    id_carrera: 0
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
                window.location.reload();

            }
            this.loading = false;
        }
    },

    }
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
