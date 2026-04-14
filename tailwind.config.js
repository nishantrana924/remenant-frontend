const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'opacity-100',
        'translate-x-0',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#01732b',
                    dark: '#014d1f',
                    light: '#019a3d',
                },
                secondary: {
                    DEFAULT: '#bfd90d',
                    dark: '#9ab00a',
                    light: '#d4f01a',
                },
                tertiary: {
                    DEFAULT: '#fff5b5',
                    dark: '#ffe982',
                    light: '#fffce8',
                },
            },
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                heading: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
