let mix = require('laravel-mix');

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

mix.js('assets/admin/js/admin.js', './assets/admin/js/ptpkg-admin.js')
   .sass('assets/admin/sass/admin.scss', './assets/admin/css/ptpkg-admin.css')
   .js('assets/public/js/public.js', './assets/public/js/ptpkg-public.js')
   .sass('assets/public/sass/public.scss', './assets/public/css/ptpkg-public.css');
