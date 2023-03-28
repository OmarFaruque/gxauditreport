let mix = require('laravel-mix');
mix.setPublicPath('assets');


mix.setResourceRoot('../');


//Public Scripts
mix
.js('app-src/js/public-main.js', 'assets/js/gx-public-score-board.js')
.sass('app-src/scss/public/app.scss', 'assets/css/public-gx-score.css');


//Admin Scripts
mix.webpackConfig({
        externals:{
            'lodash':'lodash'
        }
    })
    .js('app-src/js/boot.js', 'assets/js/boot.js')
    .js('app-src/js/main.js', 'assets/js/gx-audit.js')
    .copy('app-src/images', 'assets/images')
    .sass('app-src/scss/admin/app.scss', 'assets/css/element.css');


