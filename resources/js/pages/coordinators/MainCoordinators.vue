<template>
    <div class="loader" v-if="loading">
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Coordinadores</h3>
            <div class="input-group mb-3 w-25">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Buscar estudiante"
                    aria-label="Buscar estudiante"
                    aria-describedby="button-addon2"
                    ref="searchInput"
                    @input="filterCoordinators"
                />
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

    <div class="table-responsive">
        <table class="table table-striped" ref="coortinatorsTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Carrera</th>
                    <th class="text-center" style="width: 220px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="coordinator in coordinators" :key="coordinator.id">
                    <td>
                        {{ `${coordinator.nombres} ${coordinator.apellidos}` }}
                    </td>
                    <td>{{ coordinator.email }}</td>
                    <td>{{ coordinator.nombre_carrera }}</td>
                    <td class="text-center" style="width: 220px">
                        <div
                            class="d-flex justify-content-center gap-2 align-items-center flex-wrap"
                        >
                            <button
                                type="button"
                                class="btn btn-success btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop"
                                @click="viewCoordinator(coordinator)"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                            <button
                                class="btn btn-danger btn-sm"
                                @click="confirmDelete(coordinator.id)"
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
                        {{ createNew ? "Agregar nuevo" : "Información del" }}
                        coordinador
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveCoordinator">
                        <div>
                            <label for="nombre">Nombre: </label>
                            <input
                                class="form-control"
                                :class="{ 'is-invalid': errors.nombres }"
                                type="text"
                                v-model="form.nombres"
                                placeholder="Nombres"
                                required
                            />
                            <span
                                v-if="errors.nombres"
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                <strong>{{ errors.nombres[0] }}</strong>
                            </span>
                        </div>

                        <div>
                            <label for="apellido">Apellido: </label>
                            <input
                                class="form-control"
                                :class="{ 'is-invalid': errors.apellidos }"
                                type="text"
                                v-model="form.apellidos"
                                placeholder="Apellidos"
                                required
                            />
                            <span
                                v-if="errors.apellidos"
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                <strong>{{ errors.apellidos[0] }}</strong>
                            </span>
                        </div>

                        <div>
                            <label for="correo">Correo: </label>
                            <input
                                class="form-control"
                                :class="{ 'is-invalid': errors.email }"
                                type="text"
                                v-model="form.email"
                                :readonly="!createNew"
                                placeholder="Correo institucional"
                            />
                            <span
                                v-if="errors.email"
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                <strong>{{ errors.email[0] }}</strong>
                            </span>
                        </div>

                        <div>
                            <label for="telefono">Teléfono: </label>
                            <input
                                class="form-control"
                                type="text"
                                v-model="form.telefono"
                                placeholder="Teléfono de contacto"
                                required
                            />
                        </div>

                        <div>
                            <label for="password">Contraseña: </label>
                            <div class="input-group">
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.password }"
                                    v-model="form.password"
                                    placeholder="Contraseña"
                                    :required="createNew"
                                />
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    @click="togglePassword"
                                >
                                    {{ showPassword ? "Ocultar" : "Ver" }}
                                </button>
                            </div>
                            <span
                                v-if="errors.password"
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                <strong>{{ errors.password[0] }}</strong>
                            </span>
                        </div>

                        <div>
                            <label for="password_confirmation"
                                >Confirmar contraseña:
                            </label>
                            <div class="input-group">
                                <input
                                    :type="
                                        showRetypedPassword
                                            ? 'text'
                                            : 'password'
                                    "
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            form.password !==
                                            form.password_confirmation,
                                    }"
                                    v-model="form.password_confirmation"
                                    placeholder="Repita su contraseña"
                                    :required="createNew"
                                />
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    @click="toggleRetypedPassword"
                                >
                                    {{
                                        showRetypedPassword ? "Ocultar" : "Ver"
                                    }}
                                </button>
                            </div>
                            <span
                                v-if="
                                    form.password !== form.password_confirmation
                                "
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                <strong>Las contraseñas no coinciden</strong>
                            </span>
                        </div>

                        <div>
                            <label for="departamento">Departamento: </label>
                            <div class="input-group">
                                <select
                                    id="id_departamento"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.id_departamento,
                                    }"
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
                            <span
                                v-if="errors.id_departamento"
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                <strong>
                                    {{ errors.id_departamento[0] }}
                                </strong>
                            </span>
                        </div>

                        <div>
                            <label for="carrera">Carrera: </label>
                            <div class="input-group">
                                <select
                                    id="id_carrera"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.id_carrera }"
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
                            <span
                                v-if="errors.id_carrera"
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                <strong>{{ errors.id_carrera[0] }}</strong>
                            </span>
                        </div>

                        <div class="d-flex justify-content-end p-3">
                            <div class="btn-group btn-group-sm gap-2">
                                <button class="btn btn-primary" type="submit">
                                    {{ createNew ? "Registrar" : "Actualizar" }}
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal"
                                >
                                    Cerrar
                                </button>
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
                nombres: "",
                apellidos: "",
                email: "",
                telefono: "",
                password: "",
                password_confirmation: "",
                id_departamento: "",
                id_carrera: "",
            },
            errors: {},
        };
    },
    async mounted() {
        this.loading = true;
        try {
            await this.loadCoodinartor();
            await this.loadDepartments();
        } catch (error) {
            Swal.fire(
                "Error",
                "Ha ocurrido un error al cargar los coordinadores.",
                "error"
            );
        } finally {
            this.loading = false;
        }
    },
    watch: {
        "form.id_departamento": function (newVal) {
            if (newVal) {
                this.loadCareers();
            }
        },
    },
    methods: {
        async loadCoodinartor() {
            try {
                const response = await api.get("/coordinadores");
                this.coordinators = response.data.data;
            } catch (error) {
                Swal.fire(
                    "Error",
                    "Ha ocurrido un error al cargar los coordinadores.",
                    "error"
                );
            }
        },
        async registerCoordinator() {
            this.loading = true;
            try {
                const response = await api.post("/coordinadores", this.form);
                this.coordinators.push(response.data.data);
                Swal.fire(
                    "Registrado",
                    "El coordinador ha sido registrado.",
                    "success"
                ).then(() => {
                    window.location.reload();
                });
            } catch (error) {
                // Manejo de errores desde el servidor
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    this.errors = {
                        nombres: [
                            "Ha ocurrido un error. Por favor, inténtelo de nuevo.",
                        ],
                    };
                }
            } finally {
                this.loading = false;
            }
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
                this.loading = true;
                try {
                    await this.deleteCoordinator(id);
                    Swal.fire(
                        "Eliminado",
                        "El coordinador ha sido eliminado.",
                        "success"
                    );
                } catch (error) {
                    Swal.fire(
                        "Error",
                        "Ha ocurrido un error al eliminar el coordinador.",
                        "error"
                    );
                } finally {
                    this.loading = false;
                }
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
            this.form = { ...coordinator };
            this.createNew = false;
        },
        createNewCoordinator() {
            this.form = {
                nombres: "",
                email: "",
                telefono: "",
                id_departamento: "",
                id_carrera: "",
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
            this.loading = true;
            await api
                .patch(`/coordinadores/${id}`, this.form)
                .then(() => {
                    const index = this.coordinators.findIndex(
                        (coordinator) => coordinator.id === id
                    );
                    this.coordinators[index] = this.form;
                    Swal.fire(
                        "Actualizado",
                        "El coordinador ha sido actualizado.",
                        "success"
                    ).then(() => {
                        window.location.reload();
                    });
                })
                .catch((error) => {
                    // Manejo de errores desde el servidor
                    if (error.response && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.errors = {
                            nombres: [
                                "Ha ocurrido un error. Por favor, inténtelo de nuevo.",
                            ],
                        };
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        toggleRetypedPassword() {
            this.showRetypedPassword = !this.showRetypedPassword;
        },
        filterCoordinators() {
            const searchTerm = this.$refs.searchInput.value.trim().toLowerCase();
            const table = this.$refs.coortinatorsTable;

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
    z-index: 9999;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.loader .spinner-border {
    width: 100px;
    height: 100px;
}

.invalid-feedback {
    position: relative;
    margin-top: 0.25rem;
}
</style>
