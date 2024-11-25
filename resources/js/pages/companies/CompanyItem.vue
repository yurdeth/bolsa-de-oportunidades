<template>
    <tr>
        <td>{{ company.nombre }}</td>
        <td>{{ company.email }}</td>
        <td>{{ company.sector }}</td>
        <td>{{ company.sitio_web }}</td>
        <td class="text-center">
            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#staticBackdrop"
                @click="$emit('view-company', company)"
            >
                Ver
            </button>
            <button
                class="btn btn-danger"
                @click="$emit('delete-company', company.id)"
            >
                Eliminar
            </button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        company: {
            type: Object,
            required: true
        }
    }
};
</script>
