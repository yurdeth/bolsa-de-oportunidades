import { createRouter, createWebHistory } from "vue-router";

// Define tus rutas
const routes = [
    {
        path: ["/", "/login"],
        component: () => import("../pages/auth/MainLogin.vue"),
    },
    {
        path: "/dashboard",
        component: () => import("../pages/dashboard/MainDashboard.vue"),
    },
];

// Configura el router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
