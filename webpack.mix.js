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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .scripts(['resources/assets/js/imagesloaded.pkgd.min.js','resources/assets/js/masonry.pkgd.min.js','resources/assets/js/theme.js','resources/assets/js/jquery.autocomplete.min.js', 'resources/assets/js/jquery.form.min.js', 'resources/assets/js/jquery.maskedinput.min.js', 'resources/assets/js/cropper.min.js'],'public/js/theme.js')
   .styles(['resources/assets/sass/cropper.min.css'],'public/css/theme.css').sourceMaps();
