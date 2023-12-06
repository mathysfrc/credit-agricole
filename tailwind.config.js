/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
    colors: {
      'darkblue': '#111827',
      'white': '#FFFFFF',
      'black': '#020617',
      'blue': '#3D4797',
      'grey': '#64748B',
      'lightgrey': '#F1F5F9'
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}