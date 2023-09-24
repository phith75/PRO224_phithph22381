/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/**/*.{html,js,jsx,tsx}"],
    theme: {
        extend: {
            colors: {
                tertiary: '#EE2E24',
                secondary :'#8E8E8E',
                primary:'#121212' // Định nghĩa màu Tertiary với mã hex
        },
    },
    plugins: [],
}
}