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
            colors: {
                primary: {
                    DEFAULT: '#1E3A5F',
                    50: '#E8EDF3',
                    100: '#D1DBE7',
                    200: '#A3B7CF',
                    300: '#7593B7',
                    400: '#476F9F',
                    500: '#1E3A5F',
                    600: '#182E4C',
                    700: '#122339',
                    800: '#0C1726',
                    900: '#060C13',
                },
                accent: {
                    DEFAULT: '#2563EB',
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#2563EB',
                    600: '#1D4ED8',
                    700: '#1E40AF',
                },
                secondary: {
                    DEFAULT: '#16A34A',
                    50: '#F0FDF4',
                    100: '#DCFCE7',
                    200: '#BBF7D0',
                    300: '#86EFAC',
                    400: '#4ADE80',
                    500: '#16A34A',
                    600: '#15803D',
                    700: '#166534',
                },
                page: '#F0F2F8',
            },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
