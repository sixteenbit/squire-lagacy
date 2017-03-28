module.exports = {
	/**
	 * grunt-contrib-uglify
	 *
	 * Minify JavaScript files with UglifyJS
	 *
	 * @link https://www.npmjs.com/package/grunt-contrib-uglify
	 */
	options: {
		mangle: false,
		banner: '/*! <%= package.title %>\n' +
		' * <%= package.homepage %>\n' +
		' * Copyright (c) <%= grunt.template.today("yyyy") %>;\n' +
		' * Licensed GPLv2+\n' +
		' */\n'
	},
	main: {
		files: {
			'dist/<%= package.name %>/assets/js/color-scheme-control.js': 'dist/<%= package.name %>/assets/js/color-scheme-control.js',
			'dist/<%= package.name %>/assets/js/customize-preview.js': 'dist/<%= package.name %>/assets/js/customize-preview.js',
			'dist/<%= package.name %>/assets/js/foundation.js': 'dist/<%= package.name %>/assets/js/foundation.js',
			'dist/<%= package.name %>/assets/js/html5shiv.js': 'dist/<%= package.name %>/assets/js/html5shiv.js',
			'dist/<%= package.name %>/assets/js/skip-link-focus-fix.js': 'dist/<%= package.name %>/assets/js/skip-link-focus-fix.js',
			'dist/<%= package.name %>/assets/js/theme.js': 'dist/<%= package.name %>/assets/js/theme.js'

		}
	}
};
