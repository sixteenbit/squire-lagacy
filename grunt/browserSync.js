module.exports = {
	/**
	 * grunt-browser-sync
	 *
	 * Live CSS reload & Browser Syncing
	 *
	 * @link https://www.npmjs.com/package/grunt-browser-sync
	 */
	dev: {
		bsFiles: {
			src: [
				'*.php',
				'**/*.php',
				'Gruntfile.js',
				'assets/js/*.js',
				'*.css',
				'assets/css/*.css',
				'assets/css/*.map'
			]
		},
		options: {
			watchTask: true,
			debugInfo: true,
			logConnections: true,
			notify: true,
			proxy: 'sixteenbit.dev',
			ghostMode: {
				scroll: true,
				links: true,
				forms: true
			}
		}
	}
};
