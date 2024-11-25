import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
    theme: {
        fontSize: {
            'xs': '0.75rem',
            'sm': '1rem',
            'base': '1.1rem',
            'md': '1.2rem',
            'xl': '1.3rem',
            '2xl': '1.5rem',
            '3xl': '1.9rem',
            '4xl': '2.4rem',
            '5xl': '3.0rem',
        }
    }

};
