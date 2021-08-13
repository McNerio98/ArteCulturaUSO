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

    if (mix.inProduction()) {
        mix.version();
    }
    

mix.js('resources/js/app.js', 'public/js')
    //dashboard admin pages 
    .js('resources/js/vue-pages/app-users.js','public/js')
    .js('resources/js/vue-pages/admin/app-content.js','public/js/admin') 
    .js('resources/js/vue-pages/admin/app-populars.js','public/js/admin') 
    .js('resources/js/vue-pages/admin/app-admin.js','public/js/admin')
    .js('resources/js/vue-pages/admin/app-rubros.js','public/js/admin')
    .js('resources/js/vue-pages/admin/app-item-update.js','public/js/admin')
    .js('resources/js/vue-pages/admin/app-search.js','public/js/admin')
    .js('resources/js/vue-pages/admin/app-memories.js','public/js/admin')
    .js('resources/js/vue-pages/admin/app-resources.js','public/js/admin')
    .js('resources/js/vue-pages/app-config-user.js','public/js')
    .js('resources/js/vue-pages/app-roles.js','public/js')
    //public pages 
    .js('resources/js/vue-pages/front/app-inicio.js','public/js/front')
    .js('resources/js/vue-pages/front/app-search.js','public/js/front')
    .js('resources/js/vue-pages/front/app-resources.js','public/js/front')
    .js('resources/js/vue-pages/front/app-memories.js','public/js/front')
    .js('resources/js/vue-pages/front/app-biographies.js','public/js/front') //BIOGRAPHIES PUBLIC 
    .js('resources/js/vue-pages/front/app-events-table.js','public/js/front')//TABLE EVENTS PUBLIC 
    .js('resources/js/vue-pages/front/app-profile-edit-item.js','public/js/front')//TABLE EVENTS PUBLIC 

    
    
    //other 
    .js('resources/js/vue-pages/specific-post.js','public/js')
    .js('resources/js/api/api.service.js','public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/observatorio_styles.scss','public/css');

    mix.js('resources/js/vue-pages/front/app-profile.js','public/js/front');


