<template>
    <tr>
        <td>{{ company.nombre }}</td>
        <td>{{ company.email }}</td>
        <td>{{ company.sector }}</td>
        <td>{{ company.sitio_web }}</td>
        <td class="text-center" style="width: 220px">
            <div
                class="d-flex justify-content-center gap-2 align-items-center flex-wrap"
            >
                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop"
                    @click="$emit('view-company', company)"
                >
                    <i class="fas fa-eye"></i>
                </button>
                <button
                    class="btn btn-danger btn-sm"
                    @click="$emit('delete-company', company.id)"
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
        company: {
            type: Object,
            required: true,
        },
    },
};
</script>
