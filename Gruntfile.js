module.exports = function(grunt) {
    // Project configuration
    var autoprefixer = require('autoprefixer');
    var flexibility = require('postcss-flexibility');

	grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        postcss: {
            options: {
                map: false,
                processors: [
                    flexibility,
                    autoprefixer({
                        browsers: [
                            'Android >= 2.1',
                            'Chrome >= 21',
                            'Edge >= 12',
                            'Explorer >= 7',
                            'Firefox >= 17',
                            'Opera >= 12.1',
                            'Safari >= 6.0'
                        ],
                        cascade: false
                    })
                ]
            },
            style: {
                expand: true,
                src: [
                    'assets/css/**.css',
                    '!assets/css/**-rtl.css'
                ]
            }
        },

        rtlcss: {
            options: {
                // rtlcss options
                config: {
                    preserveComments: true,
                    greedy: true
                },
                // generate source maps
                map: false
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: "admin/assets/css",
                    src: [
                        '*.css',
                        '!*-rtl.css',
                    ],
                    dest: "admin/assets/css",
                    ext: '-rtl.css'
                },{
                    expand: true,
                    cwd: "admin/meta-assets/css",
                    src: [
                        '*.css',
                        '!*-rtl.css',
                    ],
                    dest: "admin/meta-assets/css",
                    ext: '-rtl.css'
                },{
                    expand: true,
                    cwd: "assets/css",
                    src: [
                        '*.css',
                        '!*-rtl.css',
                    ],
                    dest: "assets/css/",
                    ext: '-rtl.css'
                }]
            }
        },

		copy: {
			main: {
				options: {
					mode: true
				},
				src: [
				 '**',
                '!node_modules/**',
                '!build/**',
                '!css/sourcemap/**',
                '!.git/**',
                '!bin/**',
                '!.gitlab-ci.yml',
                '!bin/**',
                '!tests/**',
                '!phpunit.xml.dist',
                '!*.sh',
                '!*.map',
                '!Gruntfile.js',
                '!package.json',
                '!.gitignore',
                '!phpunit.xml',
                '!README.md',
                '!sass/**',
                '!codesniffer.ruleset.xml',
                '!vendor/**',
                '!composer.json',
                '!composer.lock',
                '!package-lock.json',
                '!phpcs.xml.dist',
				],
				dest: 'contact-form-7-gist-extension/'
			}
		},
		compress: {
			main: {
				options: {
					archive: 'contact-form-7-gist-extension.zip',

					mode: 'zip'
				},
				files: [
				{
					src: [
					'./contact-form-7-gist-extension/**'
					]

				}
				]
			}
		},
		clean: {
			main: ["contact-form-7-gist-extension"],
			zip: ["contact-form-7-gist-extension.zip"],
		},
		makepot: {
            target: {
                options: {
                    domainPath: '/',
                    mainFile: 'cf7-gist-ext.php',
                    potFilename: 'languages/contact-form-7-gist.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true
                    },
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },
        addtextdomain: {
            options: {
                textdomain: 'contact-form-7-gist',
                updateDomains: true
            },
            target: {
                files: {
                    src: ['*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**']
                }
            }
        },

        bumpup: {
            options: {
                updateProps: {
                    pkg: 'package.json'
                }
            },
            file: 'package.json'
        },

        replace: {
            plugin_main: {
                src: ['cf7-gist-ext.php'],
                overwrite: true,
                replacements: [
                    {
                        from: /Version: \bv?(?:0|[1-9]\d*)\.(?:0|[1-9]\d*)\.(?:0|[1-9]\d*)(?:-[\da-z-A-Z-]+(?:\.[\da-z-A-Z-]+)*)?(?:\+[\da-z-A-Z-]+(?:\.[\da-z-A-Z-]+)*)?\b/g,
                        to: 'Version: <%= pkg.version %>'
                    }
                ]
            },

            plugin_const: {
                src: ['classes/class-cf7-gist-loader.php'],
                overwrite: true,
                replacements: [
                    {
                        from: /CF7_GIST_VERSION', '.*?'/g,
                        to: 'CF7_GIST_VERSION\', \'<%= pkg.version %>\''
                    }
                ]
            }
        }
	});

    // Load grunt tasks
    grunt.loadNpmTasks('grunt-rtlcss');
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-compress' );
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-bumpup');
    grunt.loadNpmTasks('grunt-text-replace');
    grunt.loadNpmTasks( 'grunt-postcss' );

    // Autoprefix
    grunt.registerTask('style', ['postcss:style']);

    // rtlcss, you will still need to install ruby and sass on your system manually to run this
    grunt.registerTask('rtl', ['rtlcss']);
    grunt.registerTask('release', ['clean:zip', 'copy','compress','clean:main']);
    grunt.registerTask('i18n', ['addtextdomain', 'makepot']);

    // Bump Version - `grunt version-bump --ver=<version-number>`
    grunt.registerTask('version-bump', function (ver) {

        var newVersion = grunt.option('ver');

        if (newVersion) {
            newVersion = newVersion ? newVersion : 'patch';

            grunt.task.run('bumpup:' + newVersion);
            grunt.task.run('replace');
        }
    });

};
