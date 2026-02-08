/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './src/**/*.{html,js}',
        './src/**/*.{vue,js}',
    ],
    theme: {
        extend: {
            colors: {
                'primary': '#2B4570',
                'secondary': '#ffd82a',
                'tertiary': '#df8c2d',
                'quaternary': '#444444',
            }
        },
        fontFamily: {
            sans: ['Inter', 'sans-serif'],
            nunito: ['Nunito', 'sans-serif'],
            roboto: ['Roboto', 'sans-serif'],
            poppins: ['Poppins', 'sans-serif'],
            helvetica: ['Helvetica', 'sans-serif'],
        },
    }
}