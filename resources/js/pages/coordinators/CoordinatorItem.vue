<template>
    <tr>
        <td>{{ `${coordinator.nombres} ${coordinator.apellidos}` }}</td>
        <td>{{ coordinator.email }}</td>
        <td>{{ coordinator.telefono }}</td>
        <td>{{ coordinator.nombre_departamento }}</td>
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
                    @click="$emit('view-coordinator', coordinator)"
                >
                    <i class="fas fa-eye"></i>
                </button>
                <button
                    class="btn btn-danger btn-sm"
                    @click="$emit('delete-coordinator', coordinator.id)"
                >
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        coordinator: {
            type: Object,
            required: true,
        },
    },
};
</script>
