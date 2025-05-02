const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/index.css', 'public/css', [
       require('tailwindcss'),
   ]);