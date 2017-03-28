module.exports = {
	/**
	 * grunt-contrib-concat
	 *
	 * Concatenate files.
	 *
	 * @link https://www.npmjs.com/package/grunt-contrib-concat
	 */
	options: {
		stripBanners: true,
		banner: '/*! <%= package.title %>\n' +
		' * <%= package.homepage %>\n' +
		' * Copyright (c) <%= grunt.template.today("yyyy") %>;\n' +
		' * Licensed GPLv2+\n' +
		' */\n'
	},
	foundation: {
		src: [
			// Libraries required by Foundation
			'bower_components/motion-ui/dist/motion-ui.js',
			'bower_components/what-input/what-input.js',

			'bower_components/foundation-sites/dist/js/foundation.js'
		],
		dest: 'assets/js/foundation.js'
	},
	theme: {
		src: [
			// 'bower_components/matchHeight/dist/jquery.matchHeight.js',
			'assets/js/src/_*.js'
		],
		dest: 'assets/js/theme.js'
	},
	color_scheme_control: {
		src: [
			'assets/js/src/color-scheme-control.js'
		],
		dest: 'assets/js/color-scheme-control.js'
	},
	customize_preview: {
		src: [
			'assets/js/src/customize-preview.js'
		],
		dest: 'assets/js/customize-preview.js'
	},
	skip: {
		src: [
			'assets/js/src/skip-link-focus-fix.js'
		],
		dest: 'assets/js/skip-link-focus-fix.js'
	},
	html5hiv: {
		src: [
			'bower_components/html5shiv/dist/html5shiv.js'
		],
		dest: 'assets/js/html5shiv.js'
	}
};
