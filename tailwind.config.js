/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "src/Components/*.php",
      "src/Components/**/*.php",
      "src/config/*.php",
      "src/Console/*.php",
      "src/Facades/*.php",
      "src/RenderProviders/*.php",
      "src/routes/*.php",
      "src/Traits/*.php",
      "src/*.php",
      "src/**/*.blade.php",
      "src/resources/views/*.blade.php",
      "src/resources/views/components/*.blade.php",
      "src/resources/views/datatables/*.blade.php",
      "src/resources/views/fields/*.blade.php",
      "src/resources/views/fields/**/*.blade.php",
      "src/resources/views/layouts/*.blade.php",
      "src/resources/views/menu/*.blade.php",
      "src/resources/views/metrics/*.blade.php",
      "src/resources/views/widgets/*.blade.php"
  ],
  theme: {
    extend: {
        fontFamily: {
            'sans': ['Montserrat', 'sans-serif'],
        },
        colors: {
            primary: {
                50: "#f5f3ff",
                100: "#ede9fe",
                200: "#ddd6fe",
                300: "#c4b5fd",
                400: "#a78bfa",
                500: "#8b5cf6",
                600: "#7c3aed",
                700: "#6d28d9",
                800: "#5b21b6",
                900: "#4c1d95"
            }
        }
    }
  },
  plugins: [
      require('@tailwindcss/line-clamp'),
  ]
}
