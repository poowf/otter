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
        ]
    })

    .js('resources/assets/js/app.js', 'assets/js')
    .sass('resources/assets/sass/app.scss', 'public/assets/css')
    .extract(['jquery'])
    .version();