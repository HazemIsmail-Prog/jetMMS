import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                cairo: ["Cairo", ...defaultTheme.fontFamily.sans],

            },


            keyframes: {
                wiggle: {
                    '0%, 100%': { transform: 'translateX(-1px)' },
                    '50%': { transform: 'translateX(1px)' },
                }
            },
            animation: {
                wiggle: 'wiggle 0.2s ease-in-out infinite',
            },
        },
    },

    plugins: [forms, typography],
};
