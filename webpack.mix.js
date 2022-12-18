let mix = require('laravel-mix');

mix.setPublicPath('public')
    .js('src/resources/assets/js/eden.js', 'js')
    .postCss("src/resources/assets/css/eden.css", 'css', [
        require("tailwindcss"),
    ])
    .disableNotifications();
