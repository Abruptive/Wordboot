'use strict';
module.exports = function (grunt) {

	// Require all the dependencies.
	require('load-grunt-tasks')(grunt, {
		scope: 'devDependencies'
	});

	// Create the initial configuration.
	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		banner: '/*! \n * <%= pkg.name %> \n * \n * ' + 'Version: ' + '<%= pkg.version %> \n * Author: <%= pkg.author.name %> \n * Author URL: <%= pkg.author.url %> \n * ' + 'Build Date: ' + '<%= grunt.template.today("yyyy-mm-dd") %> \n */\n',

		usebanner: {
			taskName: {
				options: {
					position: 'top',
					banner: '<%= banner %>',
					linebreak: true
				},
				files: {
					src: [
						'assets/admin/css/admin.css',
						'assets/admin/css/admin.min.css',
						'assets/admin/js/admin.js',
						'assets/admin/js/admin.min.js',
						'assets/public/css/admin.css',
						'assets/public/css/admin.min.css',
						'assets/public/js/public.js',
						'assets/public/js/public.min.js'
					]
				}
			}
		},

		// Automatically run tasks and reload when resources have changed.  
		watch: {
			sass: {
				files: [
					'assets/admin/sass/**/*.{scss,sass}',
					'assets/public/sass/**/*.{scss,sass}'
				],
				tasks: ['sass:dist', 'postcss'],
				options: {
					livereload: true
				}
			},
			js: {
				files: [
					'assets/admin/js/source/**/*.js',
					'assets/public/js/source/**/*.js'
				],
				tasks: ['jshint', 'uglify', 'babel'],
				options: {
					livereload: true
				}
			}
		},

		// Compile certain SCSS files into CSS.
		sass: {
			dist: {
				options: {
					style: 'expanded'
				},
				files: [{
					expand: true,
					cwd: 'assets/admin/sass',
					src: ['**/*.scss'],
					dest: 'assets/admin/css',
					ext: '.css'
				}, {
					expand: true,
					cwd: 'assets/public/sass',
					src: ['**/*.scss'],
					dest: 'assets/public/css',
					ext: '.css'
				}]
			}
		},

		// Add vendor prefixes for better browser support.  
		postcss: {
			options: {
				map: true,
				processors: [
					require('pixrem')(),
					require('autoprefixer')({
						grid: true,
						browsers: 'last 2 versions, ie 9, ios 6, android 4'
					}),
					require('cssnano')({
						preset: 'default',
					})
				]
			},
			dist: {
				files: [{
					expand: true,
					cwd: 'assets/admin/css',
					src: ['**/*.css'],
					dest: 'assets/admin/css',
					ext: '.min.css'
				}, {
					expand: true,
					cwd: 'assets/public/css',
					src: ['**/*.css'],
					dest: 'assets/public/css',
					ext: '.min.css'
				}]
			}
		},

		// Convert modern ECMAScript code to backwards compatible JavaScript.
		babel: {
			options: {
				sourceMap: true,
				presets: ['env']
			},
			dist: {
				files: [{
					expand: true,
					cwd: 'assets/admin/js',
					src: ['*.js'],
					dest: 'assets/admin/js',
					ext: '.js'
				}, {
					expand: true,
					cwd: 'assets/public/js',
					src: ['*.js'],
					dest: 'assets/public/js',
					ext: '.js'
				}]
			}
		},

		// Lint the JavaScript using the .jshintrc file.  
		jshint: {
			options: {
				jshintrc: '.jshintrc',
				force: true
			},
			all: [
				'Gruntfile.js',
				'assets/admin/js/source/**/*.js',
				'assets/public/js/source/**/*.js'
			]
		},

		// Minify and create the maps for certain scripts.
		uglify: {
			dist: {
				options: {
					sourceMap: true
				},
				files: [{
						'assets/admin/js/admin.min.js': 'assets/admin/js/source/**/*.js'
					},
					{
						'assets/public/js/public.min.js': 'assets/public/js/source/**/*.js'
					}
				]
			}
		},

		// Order properties within the CSS assets.  
		csscomb: {
			dist: {
				options: {
					outputStyle: 'expanded',
					config: '.csscomb.json',
				},
				files: [{
					expand: true,
					cwd: 'assets/admin/css/',
					src: ['**/*.css', '**/!*.min.css'],
					dest: 'assets/admin/css/',
					ext: '.css'
				}, {
					expand: true,
					cwd: 'assets/public/css/',
					src: ['**/*.css', '**/!*.min.css'],
					dest: 'assets/public/css/',
					ext: '.css'
				}]
			}
		},

		// Beautify and adjust white space for static assets.  
		jsbeautifier: {
			css: {
				src: [
					'assets/admin/css/admin.css',
					'assets/public/css/admin.css',
				]
			},
			js: {
				src: [
					'assets/admin/js/admin.js',
					'assets/public/js/public.js',
				]
			}
		},

		// Compress image assets for production.
		imagemin: {
			dist: {
				options: {
					optimizationLevel: 7,
					progressive: true,
					interlaced: true
				},
				files: [{
					expand: true,
					cwd: 'assets/admin/img/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'assets/admin/img/'
				}, {
					expand: true,
					cwd: 'assets/public/img/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'assets/public/img/'
				}]
			}
		},

		// Generate WordPress translation files.
		makepot: {
			target: {
				options: {
					domainPath: 'languages',
					potFilename: '_s.pot',
					type: 'wp-plugin'
				}
			}
		},

		// Clean the development files.
		clean: {
			reset: [
				'.sass-cache',
				'assets/admin/css',
				'assets/admin/**/*.map',
				'assets/admin/js/admin.js',
				'assets/admin/js/admin.min.js',
				'assets/public/css',
				'assets/public/**/*.map',
				'assets/public/js/public.js',
				'assets/public/js/public.min.js',
				'languages'
			],
			build: [
				'.sass-cache',
				'node_modules',
			]
		}

	});

	// Register the 'development' task.
	grunt.registerTask(
		'development', [
			'sass:dist',
			'jshint',
			'uglify',
			'babel'
		]
	);

	// Register the 'default' task used for development.
	grunt.registerTask(
		'default', [
			'development',
			'watch'
		]
	);

	// Register the 'setup' task used for initialization.    
	grunt.registerTask(
		'setup', [
			'default'
		]
	);

	// Register the 'reset' task used to regenerate all assets.
	grunt.registerTask(
		'reset', [
			'clean:reset'
		]
	);

	// Register the 'build' task used to create the distributable version of the plugin.
	grunt.registerTask(
		'build', [
			'development',
			'csscomb',
			'postcss',
			'jsbeautifier',
			'usebanner',
			'makepot',
			'clean:build'
		]
	);

};