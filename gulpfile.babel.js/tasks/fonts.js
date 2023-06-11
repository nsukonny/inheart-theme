import gulp from 'gulp'

// Config.
import path from '../config/path'

const fonts = () => {
	return gulp.src( path.fonts.src ).pipe( gulp.dest( path.fonts.dest ) )
}

export default fonts