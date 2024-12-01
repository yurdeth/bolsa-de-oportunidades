<template>
    <div>
        <h1>Notificaciones</h1>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item" v-for="notification in notifications" :key="notification.id_aplicacion">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            :data-bs-target="'#collapse' + notification.id_aplicacion" aria-expanded="true"
                            :aria-controls="'collapse' + notification.id_aplicacion">
                        {{ notification.titulo }}
                    </button>
                </h2>
                <div :id="'collapse' + notification.id_aplicacion" class="accordion-collapse collapse show"
                     data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <h5>Descripción</h5>
                        <p>{{ notification.estado_aplicacion }}</p>
                        <h5>Estudiante</h5>
                        <p>{{ notification.nombres + ' ' + notification.apellidos }}</p>
                        <button class="btn btn-info" @click="marcarLeida(notification.id_aplicacion)">Marcar como leída</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {api} from "@/api.js";
import Swal from "sweetalert2";

export default {
    name: 'MainNotifications',
    data() {
        return {
            notifications: [],
        }
    },
    async mounted() {
        await this.getNotifications();
    },
    methods: {
        async getNotifications() {
            try {
                const response = await api.get('/notificaciones');
                this.notifications = response.data.data;
                console.log(this.notifications);
            } catch (error) {
                console.log(error);
            }
        },
        async marcarLeida(id_notificacion) {
            try {
                const response = await api.patch(`/notificaciones/leida/${id_notificacion}`, {
                    'id_notificacion': id_notificacion
                });

                if (!response.data.success) {
                    Swal.fire(
                        "Error",
                        "Error al marcar la notificacion como leida.",
                        "error"
                    ).then(() => {
                        window.location.reload();
                    });

                    return;
                }

                Swal.fire(
                    "Solicitud rechazada",
                    "Notificacion marcada como leida",
                    "success"
                ).then(() => {
                    window.location.reload();
                });

                await this.getNotifications();
            } catch (error) {
                console.log(error);
            }
        }
    }
}
</script>

<style scoped>
.accordion-button {
    cursor: pointer;
}
</style>
