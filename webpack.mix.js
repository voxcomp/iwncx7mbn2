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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .scripts(['resources/js/imagesloaded.pkgd.min.js','resources/js/masonry.pkgd.min.js','resources/js/theme.js','resources/js/jquery.autocomplete.min.js', 'resources/js/jquery.form.min.js', 'resources/js/jquery.maskedinput.min.js', 'resources/js/cropper.min.js'],'public/js/theme.js')
   .styles(['resources/sass/cropper.min.css'],'public/css/theme.css').sourceMaps();
