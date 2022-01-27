let mix = require('laravel-mix');

// config
mix
    .disableSuccessNotifications()
    .setPublicPath("public");

// assets
mix
    .js("resources/assets/js/app.js", "public/js")
    .postCss("resources/assets/css/app.css", "public/css", [
        require("tailwindcss"),
    ]);