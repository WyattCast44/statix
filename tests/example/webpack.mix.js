let mix = require('laravel-mix');

// config
mix
    .disableSuccessNotifications()
    .setPublicPath("resources/public");

// assets
mix
    .js("resources/assets/js/app.js", "resources/public/js")
    .postCss("resources/assets/css/app.css", "resources/public/css", [
        require("tailwindcss"),
    ]);