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
                <button
                    type="button"
                    class="btn btn-primary float-right"
                    data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop"
                    @click="createNewCoordinator"
                >
                    Agregar coordinador
                </button>
            </div>
        </div>
    </div>

    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Carrera</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="coordinator in coordinators" :key="coordinator.id">
                <td>{{ `${coordinator.nombres} ${coordinator.apellidos}` }}</td>
                <td>{{ coordinator.email }}</td>
                <td>{{ coordinator.nombre_carrera }}</td>
                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop"
                        @click="viewCoordinator(coordinator)"
                    >
                        Ver
                    </button>
                    <button
                        class="btn btn-danger"
                        @click="confirmDelete(coordinator.id)"
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        {{ createNew ? 'Agregar nuevo' : 'Información del' }} coordinador
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveCoordinator">
                        <div>
                            <label for="nombre">Nombre: </label>
                            <input class="form-control" type="text" v-model="form.nombres" placeholder="Nombres"
                                   required>
                        </div>

                        <div>
                            <label for="apellido">Apellido: </label>
                            <input class="form-control" type="text" v-model="form.apellidos" placeholder="Apellidos"
                                   required>
                        </div>

                        <div>
                            <label for="correo">Correo: </label>
                            <input class="form-control" type="text" v-model="form.email"
                                   :readonly="!createNew" placeholder="Correo institucional">
                        </div>

                        <div>
                            <label for="telefono">Teléfono: </label>
                            <input class="form-control" type="text" v-model="form.telefono"
                                   placeholder="Teléfono de contacto" required>
                        </div>

                        <div>
                            <label for="password">Contraseña: </label>
                            <div class="input-group">
                                <input :type="showPassword ? 'text' : 'password'" class="form-control"
                                       v-model="form.password" placeholder="Contraseña" :required="createNew">
                                <button type="button" class="btn btn-outline-secondary" @click="togglePassword">
                                    {{ showPassword ? 'Ocultar' : 'Ver' }}
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation">Confirmar contraseña: </label>
                            <div class="input-group">
                                <input :type="showRetypedPassword ? 'text' : 'password'" class="form-control"
                                       v-model="form.password_confirmation" placeholder="Repita su contraseña" :required="createNew">
                                <button type="button" class="btn btn-outline-secondary" @click="toggleRetypedPassword">
                                    {{ showRetypedPassword ? 'Ocultar' : 'Ver' }}
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="departamento">Departamento: </label>
                            <div class="input-group">
                                <select
                                    id="id_departamento"
                                    class="form-control"
                                    name="id_departamento"
                                    v-model="form.id_departamento"
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

                        <div>
                            <label for="carrera">Carrera: </label>
                            <div class="input-group">
                                <select
                                    id="id_carrera"
                                    class="form-control"
                                    name="id_carrera"
                                    v-model="form.id_carrera"
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
                                <button class="btn btn-success" type="submit">{{
                                        createNew ? 'Registrar' : 'Actualizar'
                                    }}
                                </button>
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
import {api} from "../../api";
import Swal from "sweetalert2";

export default {
    data() {
        return {
            loading: false,
            coordinators: [],
            departments: [],
            careers: [],
            selectedCoordinator: {},
            createNew: false,
            showPassword: false,
            showRetypedPassword: false,
            form: {
                nombres: '',
                apellidos: '',
                email: '',
                telefono: '',
                password: '',
                password_confirmation: '',
                id_departamento: '',
                id_carrera: ''
            }
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
        'form.id_departamento': function (newVal) {
            if (newVal) {
                this.loadCareers();
            }
        }
    },
    methods: {
        async registerCoordinator() {
            try {
                const response = await api.post('/coordinadores', this.form);
                this.coordinators.push(response.data.data);
                Swal.fire(
                    'Registrado',
                    'El coordinador ha sido registrado.',
                    'success'
                ).then(() => {
                    window.location.reload();
                });

            } catch (error) {
                console.error(error);
            }
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
        createNewCoordinator() {
            this.form = {
                nombres: '',
                email: '',
                telefono: '',
                id_departamento: '',
                id_carrera: ''
            };
            this.createNew = true;
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
                const id = this.form.id_departamento;
                const response = await api.get(`/departamentos/carreras/${id}`);
                this.careers = response.data.data;
            } catch (error) {
                console.error(error);
            }
        },
        async saveCoordinator() {
            if (this.createNew) {
                await this.registerCoordinator();
            } else {
                await this.updateCoordinator();
            }
        },
        async updateCoordinator() {
            const id = this.selectedCoordinator.id;
            await api.patch(`/coordinadores/${id}`, this.form)
                .then(() => {
                    const index = this.coordinators.findIndex(
                        (coordinator) => coordinator.id === id
                    );
                    this.coordinators[index] = this.form;
                    Swal.fire(
                        'Actualizado',
                        'El coordinador ha sido actualizado.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        toggleRetypedPassword() {
            this.showRetypedPassword = !this.showRetypedPassword;
        }
    }
};
</script>
