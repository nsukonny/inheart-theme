import gulp from 'gulp'
import browserSync from 'browser-sync'

// Plugins for SCSS.
import plumber from 'gulp-plumber'
import notify from 'gulp-notify'
import autoprefixer from 'gulp-autoprefixer'
import csso from 'gulp-csso'
import rename from 'gulp-rename'
import shorthand from 'gulp-shorthand'
import groupCssMediaQueries	from 'gulp-group-css-media-queries'
import dartSass from 'sass'
import gulpSass from 'gulp-sass'
import webpCss from 'gulp-webp-css'
import gulpIf from 'gulp-if'

// Tasks.
import clear from './tasks/clear'
import js from './tasks/js'
import img from './tasks/img'
import fonts from './tasks/fonts'

// Config.
import path from './config/path'
import app from './config/app'

const server = () => {
	browserSync.init( {
		proxy	: path.root,
		notify	: false,
		online	: true
	} )
}

const sass = gulpSass( dartSass )
const scss = () => {
	return gulp.src( path.scss.src, { sourcemaps: app.isDev } )
		.pipe( plumber( {
			errorHandler: notify.onError( error => ( {
				title	: 'ERROR IN SCSS',
				message	: error.message
			} ) )
		} ) )
		.pipe( sass() )
		.pipe( webpCss() )
		.pipe( autoprefixer() )
		.pipe( gulpIf( app.isProd, shorthand() ) )
		.pipe( gulpIf( app.isProd, groupCssMediaQueries() ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulpIf( app.isProd, csso() ) )
		.pipe( gulp.dest( path.scss.dest, { sourcemaps: app.isDev } ) )
		.pipe( browserSync.stream() )
}

const scssAcf = () => {
	return gulp.src( path.scssAcf.src, { sourcemaps: app.isDev } )
		.pipe( plumber( {
			errorHandler: notify.onError( error => ( {
				title	: 'ERROR IN SCSS ACF',
				message	: error.message
			} ) )
		} ) )
		.pipe( sass() )
		.pipe( webpCss() )
		.pipe( autoprefixer() )
		.pipe( gulpIf( app.isProd, shorthand() ) )
		.pipe( gulpIf( app.isProd, groupCssMediaQueries() ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulpIf( app.isProd, csso() ) )
		.pipe( gulp.dest( path.scssAcf.dest, { sourcemaps: app.isDev } ) )
		.pipe( browserSync.stream() )
}

const scssPages = () => {
	return gulp.src( path.scssPages.src, { sourcemaps: app.isDev } )
		.pipe( plumber( {
			errorHandler: notify.onError( error => ( {
				title	: 'ERROR IN SCSS ACF',
				message	: error.message
			} ) )
		} ) )
		.pipe( sass() )
		.pipe( webpCss() )
		.pipe( autoprefixer() )
		.pipe( gulpIf( app.isProd, shorthand() ) )
		.pipe( gulpIf( app.isProd, groupCssMediaQueries() ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulpIf( app.isProd, csso() ) )
		.pipe( gulp.dest( path.scssPages.dest, { sourcemaps: app.isDev } ) )
		.pipe( browserSync.stream() )
}

const watcher = () => {
	gulp.watch( path.php.src ).on( 'all', browserSync.reload )
	gulp.watch( path.scss.watch, scss )
	gulp.watch( path.scssAcf.watch, scssAcf )
	gulp.watch( path.scssPages.watch, scssPages )
	gulp.watch( path.js.watch, js ).on( 'all', browserSync.reload )
	gulp.watch( path.img.watch, img ).on( 'all', browserSync.reload )
	gulp.watch( path.fonts.watch, fonts ).on( 'all', browserSync.reload )
}

const build = gulp.series(
	clear,
	gulp.parallel( scss, scssAcf, scssPages, js, img, fonts )
)

const dev = gulp.series(
	build,
	gulp.parallel( watcher, server )
)

/**
 * See "scripts" object in package.json.
 *
 * By default:
 * - 'npm start' is for development;
 * - 'npm run build' is for production.
 */
export default app.isProd ? build : dev