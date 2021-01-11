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

/*mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/admin.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/admin.scss', 'public/css');*/


mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/vue-pages/app-tags.js','public/js')
    .js('resources/js/vue-pages/app-users.js','public/js')
    .js('resources/js/vue-pages/app-profile.js','public/js')
    .js('resources/js/vue-pages/app-post.js','public/js')
    .js('resources/js/vue-pages/app-request.js','public/js')
    .js('resources/js/vue-pages/specific-post.js','public/js')
    .js('resources/js/vue-pages/app-admin.js','public/js')
    .js('resources/js/api/api.service.js','public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
