<template>
    <tr v-if="idTipoUsuario !== 4">
        <td>{{ project.titulo_proyecto }}</td>
        <td>{{ project.descripcion_proyeto }}</td>
        <td>{{ project.estado_oferta }}</td>
        <td>{{ project.modalidad }}</td>
        <td class="text-center">
            <div
                class="d-flex justify-content-center gap-2 align-items-center flex-wrap"
            >
                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop"
                    @click="$emit('view-project', project)"
                >
                    Ver
                </button>
                <button
                    class="btn btn-danger btn-sm"
                    @click="$emit('delete-project', project.id)"
                >
                    Eliminar
                </button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td>{{ project.titulo }}</td>
        <td>{{ project.descripcion }}</td>
        <td>{{ project.estado_oferta_table.nombre_estado }}</td>
        <td>{{ project.modalidad_trabajo_table.nombre }}</td>
        <td class="text-center">
            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#staticBackdrop"
                @click="$emit('view-project', project)"
            >
                Ver
            </button>
            <button
                class="btn btn-danger"
                @click="$emit('delete-project', project.id)"
            >
                Eliminar
            </button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        project: {
            type: Object,
            required: true,
        },
        isTipoUsuario2: {
            type: Boolean,
            required: true,
        },
        idTipoUsuario: {
            type: Number,
            required: true,
        },
    },
    methods: {
        viewInterested() {
            this.$emit("view-interested", this.project.id);
        },
    },
};
</script>
