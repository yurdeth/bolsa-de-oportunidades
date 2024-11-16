import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    css: {
        devSourcemap: true,
    },
    plugins: [
        laravel({
            input: ["resources/js/app.js", "resources/css/app.css"],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: "localhost", // Asegúrate de usar localhost en lugar de ::1
        port: 5173, // Asegúrate de que el puerto sea correcto
    },
    build: {
        base: "/build/",
    },
});
