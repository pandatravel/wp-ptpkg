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

mix.js('src/admin/js/admin.js', 'admin/js/ptpkg-admin.js')
   .sass('src/admin/sass/admin.scss', 'admin/css/ptpkg-admin.css')
   .js('src/front/js/app.js', 'public/js/ptpkg-app.js')
   .js('src/front/js/public.js', 'public/js/ptpkg-public.js')
   .sass('src/front/sass/public.scss', 'public/css/ptpkg-public.css')
   .sass('src/front/sass/_bootstrap.scss', 'public/css/ptpkg-bootstrap.css');
