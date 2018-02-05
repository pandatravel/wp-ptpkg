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

 // put fonts in assetDir
 mix.config.publicPath = 'assets'
 mix.config.fileLoaderDirs.fonts = 'public/fonts';

mix.js('assets/src/admin/js/admin.js', 'admin/js/ptpkg-admin.js')
   .sass('assets/src/admin/sass/admin.scss', 'admin/css/ptpkg-admin.css')
   .js('assets/src/public/js/app.js', 'public/js/ptpkg-app.js')
   .js('assets/src/public/js/public.js', 'public/js/ptpkg-public.js')
   .sass('assets/src/public/sass/public.scss', 'public/css/ptpkg-public.css');
