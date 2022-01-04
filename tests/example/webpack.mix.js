let mix = require('laravel-mix');

// config
mix
    .disableSuccessNotifications()
    .setPublicPath("resources/assets/public");

// assets
mix
    .js("resources/assets/js/app.js", "resources/assets/public/js")
    .postCss("resources/assets/css/app.css", "resources/assets/public/css", [
        require("tailwindcss"),
    ]);