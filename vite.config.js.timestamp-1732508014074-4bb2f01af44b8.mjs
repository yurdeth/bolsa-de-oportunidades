// vite.config.js
import { defineConfig } from "file:///C:/Users/user/Desktop/bolsa-de-oportunidades/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/Users/user/Desktop/bolsa-de-oportunidades/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///C:/Users/user/Desktop/bolsa-de-oportunidades/node_modules/@vitejs/plugin-vue/dist/index.mjs";
var vite_config_default = defineConfig({
  css: {
    devSourcemap: true
  },
  plugins: [
    laravel({
      input: ["resources/js/app.js", "resources/css/app.css"],
      refresh: true
    }),
    vue()
  ],
  server: {
    host: "localhost",
    // Asegúrate de usar localhost en lugar de ::1
    port: 5173
    // Asegúrate de que el puerto sea correcto
  },
  build: {
    base: "/build/"
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFx1c2VyXFxcXERlc2t0b3BcXFxcYm9sc2EtZGUtb3BvcnR1bmlkYWRlc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcdXNlclxcXFxEZXNrdG9wXFxcXGJvbHNhLWRlLW9wb3J0dW5pZGFkZXNcXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L1VzZXJzL3VzZXIvRGVza3RvcC9ib2xzYS1kZS1vcG9ydHVuaWRhZGVzL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSBcInZpdGVcIjtcbmltcG9ydCBsYXJhdmVsIGZyb20gXCJsYXJhdmVsLXZpdGUtcGx1Z2luXCI7XG5pbXBvcnQgdnVlIGZyb20gXCJAdml0ZWpzL3BsdWdpbi12dWVcIjtcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBjc3M6IHtcbiAgICAgICAgZGV2U291cmNlbWFwOiB0cnVlLFxuICAgIH0sXG4gICAgcGx1Z2luczogW1xuICAgICAgICBsYXJhdmVsKHtcbiAgICAgICAgICAgIGlucHV0OiBbXCJyZXNvdXJjZXMvanMvYXBwLmpzXCIsIFwicmVzb3VyY2VzL2Nzcy9hcHAuY3NzXCJdLFxuICAgICAgICAgICAgcmVmcmVzaDogdHJ1ZSxcbiAgICAgICAgfSksXG4gICAgICAgIHZ1ZSgpLFxuICAgIF0sXG4gICAgc2VydmVyOiB7XG4gICAgICAgIGhvc3Q6IFwibG9jYWxob3N0XCIsIC8vIEFzZWdcdTAwRkFyYXRlIGRlIHVzYXIgbG9jYWxob3N0IGVuIGx1Z2FyIGRlIDo6MVxuICAgICAgICBwb3J0OiA1MTczLCAvLyBBc2VnXHUwMEZBcmF0ZSBkZSBxdWUgZWwgcHVlcnRvIHNlYSBjb3JyZWN0b1xuICAgIH0sXG4gICAgYnVpbGQ6IHtcbiAgICAgICAgYmFzZTogXCIvYnVpbGQvXCIsXG4gICAgfSxcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFnVSxTQUFTLG9CQUFvQjtBQUM3VixPQUFPLGFBQWE7QUFDcEIsT0FBTyxTQUFTO0FBRWhCLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLEtBQUs7QUFBQSxJQUNELGNBQWM7QUFBQSxFQUNsQjtBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ0wsUUFBUTtBQUFBLE1BQ0osT0FBTyxDQUFDLHVCQUF1Qix1QkFBdUI7QUFBQSxNQUN0RCxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsSUFDRCxJQUFJO0FBQUEsRUFDUjtBQUFBLEVBQ0EsUUFBUTtBQUFBLElBQ0osTUFBTTtBQUFBO0FBQUEsSUFDTixNQUFNO0FBQUE7QUFBQSxFQUNWO0FBQUEsRUFDQSxPQUFPO0FBQUEsSUFDSCxNQUFNO0FBQUEsRUFDVjtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
