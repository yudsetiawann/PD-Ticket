// 1. Import presetnya
import preset from "./vendor/filament/filament/tailwind.config.preset.js";
// (Kita tambahkan .js di akhir untuk keamanan modul ES)
import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    // 2. Muat presetnya
    presets: [preset],

    // 3. GABUNGKAN content, jangan di-overwrite
    content: [
        // 3a. Ambil semua path internal dari preset Filament
        ...preset.content,

        // 3b. Tambahkan path view user, Breeze, dan admin kustom Anda
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php", // <-- Ini akan memindai Breeze
        "./app/Filament/**/*.php", // <-- Ini akan memindai halaman kustom admin
        "./resources/views/filament/**/*.blade.php", // <-- Ini akan memindai view kustom admin
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        // Tetap kosongkan
    ],
};
