const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Http/Controllers/**/*.php',
    ],
    safelist: [
        'opacity-100',
        'translate-x-0',
        {
            pattern: /(bg|text|border)-(orange|blue|purple|indigo|emerald|rose|slate|emerald)-(50|100|200|500|600)/,
        },
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
                orange: {
                    50: '#FFF7ED',
                    100: '#FFEDD5',
                    500: '#F97316',
                    600: '#EA580C',
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
