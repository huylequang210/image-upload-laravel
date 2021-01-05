const mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js([
  'resources/js/entry.js',
  'resources/js/home.js'
  ], 'public/js/home.js')
  .js([
    'resources/js/entry.js',
    'resources/js/welcome.js'
  ], 'public/js/welcome.js')
  .js([
    'resources/js/entry.js',
    'resources/js/gallery.js'
  ], 'public/js/gallery.js')
  .js('resources/js/profile.js', 'public/js/profile.js')
  .js('resources/js/admin.js', 'public/js/admin.js')
  .sass('resources/sass/app.scss', 'public/css')
    .options({
      processCssUrls: false,
      postCss: [ tailwindcss('./tailwind.config.js') ],
    })
    .purgeCss({
      //enabled: mix.inProduction(),
      //folders: ['src', 'templates'],
      //extensions: ['html', 'js', 'php', 'vue'],
    })
