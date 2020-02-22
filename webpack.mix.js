const mix = require('laravel-mix');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const imageminMozjpeg = require('imagemin-mozjpeg');

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
    .setPublicPath('public')
    .options({
        fileLoaderDirs: {
            fonts: 'assets/fonts'
        }
    })
    .webpackConfig({
        plugins: [
            new CopyWebpackPlugin([{
                from: 'resources/assets/img',
                to: 'assets/img', // Laravel mix will place this in 'public/img'
            }]),
            new ImageminPlugin({
                test: /\.(jpe?g|png|gif|svg)$/i,
                plugins: [
                    imageminMozjpeg({
                        quality: 100,
                    })
                ]
            })
        ],
        /*externals: {
            'sparkline': '__webpack_require__("./node_modules/tabler/dist/js/vendors/jquery.sparkline.min.js")',
            'circle-progress': '__webpack_require__("./node_modules/tabler/dist/js/vendors/circle-progress.min.js")'
        }*/
    })

    .js('resources/assets/js/bootstrap.js', 'assets/js')
    .js('resources/assets/js/app.js', 'assets/js')
    .js('node_modules/tabler/dist/js/tabler.min.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/cleanpaste/trumbowyg.cleanpaste.min.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js', 'assets/js')
    .js('node_modules/trumbowyg/dist/plugins/history/trumbowyg.history.min.js', 'assets/js')

    .copy('resources/assets/css/app.css','public/assets/css/app.css')

    .copy('node_modules/trumbowyg/dist/ui/trumbowyg.min.css','public/assets/css/trumbowyg.css')
    .copy('node_modules/trumbowyg/dist/plugins/colors/ui/trumbowyg.colors.min.css', 'public/assets/css/trumbowyg.colors.css')

    .copy('node_modules/tabler/dist/css/tabler.css', 'public/assets/css/tabler.css')
    /*.copy('node_modules/tabler/dist/fonts/feather/feather-webfont.eot', 'public/assets/fonts/feather/feather-webfont.eot')
    .copy('node_modules/tabler/dist/fonts/feather/feather-webfont.svg', 'public/assets/fonts/feather/feather-webfont.svg')
    .copy('node_modules/tabler/dist/fonts/feather/feather-webfont.ttf', 'public/assets/fonts/feather/feather-webfont.ttf')
    .copy('node_modules/tabler/dist/fonts/feather/feather-webfont.woff', 'public/assets/fonts/feather/feather-webfont.woff')*/
    .copy('node_modules/trumbowyg/dist/ui/icons.svg', 'public/assets/fonts/trumbowygicons.svg')

    .extract(['jquery'])
    .version();
