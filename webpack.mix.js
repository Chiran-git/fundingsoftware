const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// We don't want to see the fonts and images being copied in the console output
mix.webpackConfig({
    stats : {
        assets : true,
        excludeAssets : [/.*fonts\/.*/ , /.*images\/.*/],
    }
});

mix.js("resources/assets/js/app.js", "public/js")
    .extract([
        'jquery',
        'bootstrap',
        'lodash',
        'promise',
        'moment-timezone',
        'vue',
        'vue-router',
        'vue-sweetalert2',
        'vue-smoothscroll',
        'vue-select',
        'vue-trend-chart',
        'vue-toastr',
        'vue-table-component'
    ])
    .styles(
        [
            "resources/assets/css/jquery-password-validator.css",
        ],
        "public/css/style.css"
    )
    .combine(
        [
            "resources/assets/js/libs/clipboard.min.js",
            "resources/assets/js/functions.js",
            "resources/assets/js/libs/hideShowPassword.min.js",
            "resources/assets/js/libs/jquery.password-validator.js",
        ],
        "public/js/plugins.js"
    )
    .sass("resources/assets/sass/app.scss", "public/css")
    .sass("resources/assets/sass/auth.scss", "public/css")
    .version();
