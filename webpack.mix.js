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

mix.js('resources/js/app.js', 'public/js')
    .scripts([
        'public/js/2020/dropzone.js',
        'public/js/2020/adminLTE.min.js',
        'public/js/2020/adminLTE.slimscroll.min.js',
        'public/js/2020/sweetalert.js'
    ], 'public/js/LTE.js')
   .sass('resources/sass/app.scss', 'public/css');
