/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./app/Views/**/*.php",
        "./public/**/*.js",
    ],
    darkMode: 'class', // Soporte para modo oscuro mediante clase
    theme: {
        extend: {
            colors: {
                primary: '#3b82f6', // Azul primario usado en el proyecto
                surface: {
                    light: '#ffffff',
                    dark: '#1c2127',
                }
            },
        },
    },
    plugins: [],
}
