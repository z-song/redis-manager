const mix = require('laravel-mix');
// const webpack = require('webpack');

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

mix
    .options({
        processCssUrls: false,
        uglify: {
            compress: {
                drop_console: false,
            }
        }
    })
    .setPublicPath('public')
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copy('resources/assets/img', 'public/img')
    .sourceMaps()
    .version()
    // .copy('public/fonts', '../../../public/fonts')
    .copy('node_modules/font-awesome/fonts', 'public/fonts')
    .copy('public', '../../../public/vendor/redis-manager');


mix.webpackConfig({
    devtool: "source-map",
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.runtime.esm.js'
        }
    }
});
