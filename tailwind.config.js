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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                espresso: {
                    DEFAULT: '#4A3525',
                    light: '#5E4430',
                    dark: '#37271B',
                },
                latte: {
                    DEFAULT: '#D4A373',
                    light: '#E2BCA4',
                    dark: '#C28C57',
                },
                offwhite: '#FAFAFA',
                charcoal: '#2B2B2B',
            },
        },
    },

    plugins: [forms],
};
