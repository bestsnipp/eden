const defaultTheme = require("tailwindcss/defaultTheme");
const defaultConfig = require("tailwindcss/defaultConfig");
const eden = require("./tailwind.plugin");

/** @type {import('tailwindcss').Config} */

module.exports = {
    darkMode: 'class',

    content: [
        "src/*.php",
        "src/**/*.js",
        "src/**/*.php",
        "src/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                //'sans': ['Montserrat', 'sans-serif'],
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: eden.generateTailwindColors()
        }
    },

    safelist: [
        {
            pattern: /(.+)-primary-(.+)/,
            variants: ['*'],
        },
    ],

    plugins: [
        require('@tailwindcss/line-clamp'),
        function ({ addBase }) {
            addBase({ ':root': eden.generateRootCSSVariables() })
        },
    ]
}
