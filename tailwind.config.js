import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js",
        // 'node_modules/pagedone/dist/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
            },
            height: {
                '128': '32rem',   // tương đương với 512px
                '144': '36rem',   // tương đương với 576px
                '160': '40rem',   // tương đương với 640px
                '192': '48rem',   // tương đương với 768px
            }
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin'),
        require('@tailwindcss/aspect-ratio'),
        // require('pagedone/plugin')  
    ],
};
