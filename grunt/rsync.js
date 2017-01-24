module.exports = {
	/**
	 * grunt-rsync
	 *
	 * A Grunt task for accessing the file copying and syncing capabilities of the rsync
	 * command line utility. Uses the rsyncwrapper npm module for the core functionality.
	 *
	 * @link https://www.npmjs.com/package/grunt-rsync
	 */
	options: {
		args: ["--verbose"],
		recursive: true,
		delete: true // Careful this option could cause data loss, read the docs!
	},
	sixteenbit: {
		options: {
			src: "./dist/<%= package.name %>",
			dest: "/var/www/sixteenbit.com/wp-content/themes",
			host: "root@45.55.205.75"
		}
	},
	byjustin: {
		options: {
			src: "./dist/<%= package.name %>",
			dest: "/var/www/byjust.in/wp-content/themes",
			host: "root@45.55.205.75"
		}
	}
};
