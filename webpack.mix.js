const mix = require('laravel-mix');
//const CompressionPlugin = require('compression-webpack-plugin');

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
mix.browserSync('localhost:8000');
mix.js('resources/js/app.js', 'public/js').
	/**
	 * Auth
	 */
	/**
	 * Dashboard commom
	 */
	js('public/dashboard/js/navigation/navigation.js','public/dashboard/compiled/js/navigation/navigation.min').
	js('public/dashboard/js/alerts/pnotify.js','public/dashboard/compiled/js/alerts/pnotify.min').
	js('public/dashboard/js/errors/error_placement.js','public/dashboard/compiled/js/errors/error_placement.min').
	js('public/dashboard/js/errors/server_error_handler.js','public/dashboard/compiled/js/errors/server_error_handler.min').
	js('public/dashboard/js/selectors/select2.js','public/dashboard/compiled/js/selectors/select2.min').
	js('public/dashboard/js/common/manage.js','public/dashboard/compiled/js/common/manage.min').
	/**
	 * Reports
	 */
//	js('public/dashboard/js/common/manage-report.js','public/dashboard/compiled/js/common/manage-report.min').
	/**
	 * Analysis
	 */
	js('public/dashboard/js/analysis/manage.js','public/dashboard/compiled/js/analysis/manage.min').
	/**
	 * Dashboard
	 */
	js('public/dashboard/js/dashboard/manage.js','public/dashboard/compiled/js/dashboard/manage.min').
	/**
	 * Complaints
	 */
	js('public/dashboard/js/complaints/setup.js','public/dashboard/compiled/js/complaints/setup.min').
	js('public/dashboard/js/complaints/manage.js','public/dashboard/compiled/js/complaints/manage.min').
	js('public/dashboard/js/complaints/action.js','public/dashboard/compiled/js/complaints/action.min').
	js('public/dashboard/js/complaints/amendment.js','public/dashboard/compiled/js/complaints/amendment.min').
	/**
	 * Search
	 */
	 js('public/dashboard/js/search/manage.js','public/dashboard/compiled/js/search/manage.min').
	/**
	 * Categories
	 */
	js('public/dashboard/js/categories/setup.js','public/dashboard/compiled/js/categories/setup.min').
	js('public/dashboard/js/categories/manage.js','public/dashboard/compiled/js/categories/manage.min').
	/**
	 * Modes
	 */
	js('public/dashboard/js/modes/setup.js','public/dashboard/compiled/js/modes/setup.min').
	js('public/dashboard/js/modes/manage.js','public/dashboard/compiled/js/modes/manage.min').
	/**
	 *  Sub-Categories
	 */
	js('public/dashboard/js/sub_categories/setup.js','public/dashboard/compiled/js/sub_categories/setup.min').
	js('public/dashboard/js/sub_categories/manage.js','public/dashboard/compiled/js/sub_categories/manage.min').
	/**
	 * Zones
	 */
	js('public/dashboard/js/zones/setup.js','public/dashboard/compiled/js/zones/setup.min').
	js('public/dashboard/js/zones/manage.js','public/dashboard/compiled/js/zones/manage.min').
	/**
	 * Regions
	 */
	js('public/dashboard/js/regions/setup.js','public/dashboard/compiled/js/regions/setup.min').
	js('public/dashboard/js/regions/manage.js','public/dashboard/compiled/js/regions/manage.min').
	/**
	 * Branches
	 */
	js('public/dashboard/js/branches/manage.js','public/dashboard/compiled/js/branches/manage.min').
	/**
	 *  Reports
	 */
	js('public/dashboard/js/reports/manage.js','public/dashboard/compiled/js/reports/manage.min').
	/**
	 * Swwetalert
	 */
	js('public/dashboard/js/alerts/sweetalert2.js','public/dashboard/compiled/js/alerts/sweetalert2.min');
	
mix.copy('node_modules/jquery/dist/jquery.min.js','public/dashboard/compiled/js/plugins/jquery.min.js').
	copy('node_modules/sweetalert2/dist/sweetalert2.all.min.js','public/dashboard/compiled/js/plugins/sweetalert2.all.min.js').
	copy('node_modules/sweetalert2/dist/sweetalert2.min.css','public/dashboard/compiled/css/plugins/sweetalert2.min.css').

	copy('node_modules/chart.js/dist/Chart.min.js','public/dashboard/compiled/js/plugins/chart.min.js').
	copy('node_modules/chart.js/dist/Chart.bundle.min.js','public/dashboard/compiled/js/plugins/chart.bundle.min.js').
	copy('node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js','public/dashboard/compiled/js/plugins/chart.datalabels.min.js').
	copy('node_modules/moment/min/moment.min.js','public/dashboard/compiled/js/plugins/moment.min.js').
	copy('node_modules/chart.js/dist/Chart.min.css','public/dashboard/compiled/css/plugins/chart.min.css').
	
    copy('node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js','public/dashboard/compiled/js/plugins/bootstrap-colorpicker.min.js').
    copy('node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css','public/dashboard/compiled/css/plugins/bootstrap-colorpicker.min.css');

mix.styles('public/dashboard/css/custom-setup.css','public/dashboard/compiled/css/custom-setup.min.css').
	styles('public/dashboard/css/my-custom.css','public/dashboard/compiled/css/my-custom.min.css').
	styles('public/dashboard/css/preloader.css','public/dashboard/compiled/css/preloader.min.css').
	styles('public/dashboard/css/fonts/fonts.css','public/dashboard/compiled/css/fonts.min.css').
	/**
	
	 * Auth New  */
	styles('public/dashboard/css/login/bootstrap.min.css','public/dashboard/compiled/css/login/bootstrap.min.css').
	styles('public/dashboard/css/login/style.css','public/dashboard/compiled/css/login/style.min.css').	
		
	/**
	 * Error
	 */
	styles('public/dashboard/css/error.css','public/dashboard/compiled/css/error.min.css').
options({
		processCssUrls : true,
		uglify: true
//	    uglify: {
//            compress: {
//                warnings: false,
//                drop_console: true,
//                warnings: false
//            },
//            output: {
//                comments: false
//            }
//		}
});
//webpackConfig({
//    plugins: [
//        new CompressionPlugin({
////          asset: '[path].gz[query]',
//          algorithm: 'gzip',
//          test: /\.js$|\.css$|\.html$|\.svg$/,
//          threshold: 10240,
//          minRatio: 0.8,
//        }),
//      ]
//});

if (mix.inProduction()) {
	mix.version();
}
