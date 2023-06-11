const isProd    = process.argv.includes( '--production' )
const isDev     = ! isProd

export default {
	isProd	: isProd,
	isDev	: isDev,

	webpack	: {
		module		: {
			rules: [
				{
					test: /\.js$/,
					use: {
						loader: 'babel-loader',
						options: {
							presets: ['@babel/preset-env']
						}
					}
				}
			]
		},
		mode		: isProd ? 'production' : 'development',
		output		: {
			filename: pathData => pathData.chunk.name === 'main' ? '[name].min.js' : '[name]/[name].min.js'
		},
		externals	: {
			jquery: 'jQuery'
		}
	},

	imagemin: {
		verbose: true
	}
}