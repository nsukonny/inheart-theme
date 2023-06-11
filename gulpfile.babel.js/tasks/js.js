import gulp from 'gulp'

// Plugins.
import plumber from 'gulp-plumber'
import notify from 'gulp-notify'
import webpack from 'webpack-stream'
import named from 'vinyl-named'

// Config.
import path from '../config/path'
import app from '../config/app'

const js = () => {
	return gulp.src( path.js.src, { sourcemaps: app.isDev } )
		.pipe( plumber( {
			errorHandler: notify.onError( error => ( {
				title	: 'ERROR IN JAVASCRIPT',
				message	: error.message
			} ) )
		} ) )
		.pipe( named() )
		.pipe( webpack( app.webpack ) )
		.pipe( gulp.dest( path.js.dest, { sourcemaps: app.isDev } ) )
}

export default js