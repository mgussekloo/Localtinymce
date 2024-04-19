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

mix.webpackConfig({
  watchOptions: {
    ignored: /node_modules/
  }
})

require('./nova.mix')

mix
  .setPublicPath('dist')
  .sass('resources/sass/field.scss', 'css')
  .js('resources/js/field.js', 'js').vue({ version: 3 })
  .nova('gusmanson/localtinymce');

