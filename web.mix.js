const mix = require('laravel-mix');
const VitePlugin = require('laravel-vite-plugin');

mix.js('resources/js/app.js', 'public/js')
   .vite()
   .plugin(VitePlugin);
