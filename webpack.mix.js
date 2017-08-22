const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/admin.js', 'public/js')
   .js('resources/assets/js/agent.js', 'public/js')
   .less('resources/assets/less/admin.less', 'public/css')
   .less('resources/assets/less/agent.less', 'public/css')
   .less('resources/assets/less/login.less', 'public/css');

if (mix.inProduction()) {
    mix.version();
}
