import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: false,
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                DEFAULT: '#0d9488', // teal-600
                light: '#14b8a6',   // teal-500
                dark: '#0f766e',    // teal-700
                },
                accent: {
                DEFAULT: '#FF8A00', // warm orange custom
                light: '#ff9c33',
                dark: '#cc6e00',
                },
                success: {
                DEFAULT: '#16a34a', // green-600
                light: '#22c55e',   // green-500
                dark: '#15803d',    // green-700
                },
                warning: {
                DEFAULT: '#f59e0b', // amber-500
                light: '#fbbf24',   // amber-400
                dark: '#b45309',    // amber-700
                },
                muted: {
                DEFAULT: '#6b7280', // gray-500
                light: '#9ca3af',   // gray-400
                dark: '#4b5563',    // gray-600
                },
            },
            fontFamily: {
                sans: ['Noto Sans', 'Inter', 'system-ui', 'sans-serif'],
            },
            },
    },

    plugins: [forms],
};
