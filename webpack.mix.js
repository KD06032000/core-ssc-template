let mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */
mix.autoload({
    jquery: ['$', 'window.jQuery']
});
mix.combine([
    'public/js/jquery.min.js',
    'public/js/popper.min.js',
], 'public/js/head.js');
mix.combine([
    'public/js/bootstrap.min.js',
    'public/js/jquery.fancybox.min.js',
    'public/js/jquery.mmenu.all.min.js',
    'public/js/lazyload.min.js',
    'public/js/bootstrap-datepicker.min.js',
    'public/js/bootstrap-datepicker.vi.min.js',
    'public/js/fulcalendar.min.js',
    'public/js/moment.min.js',
    'public/js/popper.min.js',
    'public/js/slick.min.js',
    'public/js/private.js',
], 'public/js/app.js');
mix.styles(
    [
        // 'public/fonts/Fontawesome/all.min.css',
        'public/css/jquery.fancybox.min.css',
        'public/css/bootstrap.min.css',
        'public/css/slick.min.css',
        'public/css/slick-theme.min.css',
        'public/css/jquery.mmenu.all.css',
        'public/css/fulcalendar.min.css',
        'public/css/bootstrap-datepicker.css',
        // 'public/css/fonts.css',
        'public/css/reset.css',
        'public/css/styles.css',
        'public/css/responsive.css',
    ],
    'public/css/app.css').options({
    processCssUrls: false
}).sourceMaps();
// mix.copyDirectory('public/fonts/fontawesome-pro-5.8.2-web/webfonts/', 'public/webfonts/');
if (mix.inProduction()) {
    mix.version();
}
mix.setPublicPath('/');
