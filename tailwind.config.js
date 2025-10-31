import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './vendor/laravel/breeze/**/*.blade.php',
    ],

    safelist: [
        'btn','btn-primary','btn-secondary','btn-accent','btn-outline',
        'card','card-body','badge','badge-primary','badge-success','badge-accent','badge-neutral',
        'status-todo','status-in_progress','status-review','status-done',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
