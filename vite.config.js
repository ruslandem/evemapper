import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./resources/assets/js"),
      "#": path.resolve(__dirname, "./resources/assets/sass"),
      "@modules": path.resolve(__dirname, "./node_modules"),
    },
  },
  plugins: [
    vue(),
    laravel({
      input: ["./resources/assets/js/app.ts"],
      refresh: true,
    }),
  ],
  build: {
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes("node_modules")) {
            return id
              .toString()
              .split("node_modules/")[1]
              .split("/")[0]
              .toString();
          }
        },
      },
    },
  },
});
