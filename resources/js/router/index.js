import { createRouter, createWebHistory } from "vue-router";

// Define tus rutas
const routes = [
    {
        path: "/",
        component: () => import("../pages/auth/MainLogin.vue"),
    },
    {
        path: "/login",
        component: () => import("../pages/auth/MainLogin.vue"),
    },
    {
        path: "/changePassword",
        name: "changePassword",
        component: () => import("../pages/auth/ChangePassword.vue"),
    },
    {
        path: "/dashboard",
        middleware: "auth",
        component: () => import("../layouts/MainLayout.vue"),
        children: [
            {
                path: "/dashboard",
                component: () => import("../pages/dashboard/MainDashboard.vue"),
            },
            {
                path: "/empresas",
                component: () => import("../pages/companies/MainCompanies.vue"),
            },
            {
                path: "/usuarios",
                component: () => import("../pages/users/MainUsers.vue"),
            },
            {
                path: "/estudiantes",
                component: () => import("../pages/students/MainStudents.vue"),
            },
            {
                path: "/coordinadores",
                component: () =>
                    import("../pages/coordinators/MainCoordinators.vue"),
            },
            {
                path: "/proyectos",
                component: () => import("../pages/projects/MainProjects/MainProjects.vue"),
            },
            {
                path: "/proyectos/publicados",
                component: () => import("../pages/projects/MainProjects/Published.vue"),
            },
            {
                path: "/proyectos-activos",
                component: () => import("../pages/projects/ActiveProjects/ActiveProjects.vue"),
            },
            {
                path: "/notificaciones",
                component: () => import("../pages/notifications/MainNotifications.vue"),
            },
        ],
    },
    {
        path: "/register",
        component: () => import("../pages/auth/MainRegisterCompanies.vue"),
    },
];

// Configura el router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
