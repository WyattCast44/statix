let mix = require('laravel-mix');

// config
mix
    .disableSuccessNotifications()
    .setPublicPath("public");

// assets
mix
    .js("resources/js/app.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        require("tailwindcss"),
    ]);