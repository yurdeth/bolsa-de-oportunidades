import { createRouter, createWebHistory } from "vue-router";

// Define tus rutas
const routes = [
    {
        path: "/",
        component: () => import("../layouts/LayoutWebsite.vue"),
        children: [
            {
                path: "/",
                component: () => import("../pages/websites/MainHome.vue"),
            },
        ],
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
