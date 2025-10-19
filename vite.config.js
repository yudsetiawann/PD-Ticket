import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css", // CSS untuk view user
                // "resources/css/filament/admin/theme.css", // CSS untuk panel admin
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
});
