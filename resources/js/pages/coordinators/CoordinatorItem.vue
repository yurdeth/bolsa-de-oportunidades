<template>
    <tr>
        <td>{{ `${coordinator.nombres} ${coordinator.apellidos}` }}</td>
        <td>{{ coordinator.email }}</td>
        <td>{{ coordinator.telefono }}</td>
        <td>{{ coordinator.nombre_departamento }}</td>
        <td>{{ coordinator.nombre_carrera }}</td>
        <td class="text-center">
            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#staticBackdrop"
                @click="$emit('view-coordinator', coordinator)"
            >
                Editar
            </button>
            <button
                class="btn btn-danger"
                @click="$emit('delete-coordinator', coordinator.id)"
            >
                Eliminar
            </button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        coordinator: {
            type: Object,
            required: true
        }
    }
};
</script>
