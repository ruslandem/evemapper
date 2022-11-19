const { minify } = require("laravel-mix");
const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copy("resources/assets/img", "public/img");

mix.js("resources/assets/js/app.js", "public/js");

mix.postCss("resources/assets/css/app.css", "public/css");
mix.postCss("node_modules/bulma/css/bulma.min.css", "public/css");
mix.postCss(
    "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
    "public/css/fontawesome.min.css"
);
mix.postCss("node_modules/toastify-js/src/toastify.css", "public/css");
