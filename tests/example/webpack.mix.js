let mix = require('laravel-mix');

mix
    .js("resources/assets/js/app.js", "resources/assets/public/js")
    .postCss("resources/assets/css/app.css", "resources/assets/public/css", [
        require("tailwindcss"),
    ]);