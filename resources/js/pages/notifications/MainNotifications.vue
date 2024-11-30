<template>
    <div>
        <h1>Notificaciones</h1>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item" v-for="notification in notifications" :key="notification.id_notificacion">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            :data-bs-target="'#collapse' + notification.id_notificacion" aria-expanded="true"
                            :aria-controls="'collapse' + notification.id_notificacion">
                        {{ notification.nombre }}
                    </button>
                </h2>
                <div :id="'collapse' + notification.id_notificacion" class="accordion-collapse collapse show"
                     data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <h5>Descripción</h5>
                        <p>{{ notification.descripcion }}</p>
                        <h5>Mensaje</h5>
                        <p>{{ notification.mensaje }}</p>
                        <h5>Estudiante</h5>
                        <p>{{ notification.nombres + ' ' + notification.apellidos }}</p>
                        <button class="btn btn-info">Marcar como leída</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {api} from "@/api.js";

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
