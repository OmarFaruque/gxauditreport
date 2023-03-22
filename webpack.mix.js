let mix = require('laravel-mix');
mix.setPublicPath('assets');

let lodash = require('lodash');
// window.lodash = _.noConflict()

mix.setResourceRoot('../');
mix.webpackConfig({
        externals:{
            'lodash':'lodash'
        }
    })
    .js('app-src/js/boot.js', 'assets/js/boot.js')
    .js('app-src/js/main.js', 'assets/js/gx-audit.js')
    .copy('app-src/images', 'assets/images')
    .sass('app-src/scss/admin/app.scss', 'assets/css/element.css');