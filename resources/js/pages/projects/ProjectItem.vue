<template>
    <tr>
        <td>{{ project.titulo_proyecto }}</td>
        <td>{{ project.descripcion_proyeto }}</td>
        <td>{{ project.estado_oferta }}</td>
        <td>{{ project.modalidad }}</td>
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
            required: true
        }
    }
};
</script>
