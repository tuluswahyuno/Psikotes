import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Public Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Public Sans', 'sans-serif'],
            },
            colors: {
                "primary": "#135bec",
                "background-light": "#f6f6f8",
                "background-dark": "#101622",
                "success": "#07883b",
                "danger": "#d32f2f",
            },
        },
    },

    plugins: [forms],
};
