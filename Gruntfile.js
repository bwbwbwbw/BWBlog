module.exports = function (grunt)
{

     grunt.initConfig(
     {

          pkg: grunt.file.readJSON('package.json'),

          stylus:
          {
               theme:
               {
                    expand:   true,
                    cwd:      '.',
                    src:      ['themes/*/css/*.styl', 'native/css/*.styl'],
                    dest:     '.',
                    extDot:   'last',
                    ext:      '.css'
               }
          },

          autoprefixer:
          {
               theme:
               {
                    expand:   true,
                    cwd:      '.',
                    src:      ['themes/*/css/*.css', 'native/css/*.css'],
                    dest:     '.',
                    extDot:   'last',
                    ext:      '.css'
               }
          },

          cssmin:
          {
               theme:
               {
                    expand:   true,
                    cwd:      '.',
                    src:      ['themes/*/css/*.css', 'native/css/*.css'],
                    dest:     '.',
                    extDot:   'last',
                    ext:      '.css'
               }
          },

          copy: {
               theme:
               {
                    expand:   true,
                    cwd:      '.',
                    src:      ['themes/*/js/*.js', '!themes/*/js/*.min.js'],
                    dest:     '.',
                    extDot:   'last',
                    ext:      '.min.js'
               }
          },

          uglify: {
               theme:
               {
                    expand:   true,
                    cwd:      '.',
                    src:      ['themes/*/js/*.js', '!themes/*/js/*.min.js'],
                    dest:     '.',
                    extDot:   'last',
                    ext:      '.min.js'
               }
          },

          watch:
          {
               css:
               {
                    files: ['themes/*/js/**/*.js', '!themes/*/js/*.min.js', 'themes/*/css/**/*.styl', 'native/css/**/*.styl'],
                    tasks: ['stylus', 'autoprefixer', 'copy']
               }
          }

     });

     grunt.loadNpmTasks('grunt-contrib-watch');
     grunt.loadNpmTasks('grunt-contrib-copy');
     grunt.loadNpmTasks('grunt-contrib-stylus');
     grunt.loadNpmTasks('grunt-contrib-cssmin');
     grunt.loadNpmTasks('grunt-contrib-uglify');
     grunt.loadNpmTasks('grunt-autoprefixer');

     grunt.registerTask('default', ['stylus', 'autoprefixer', 'copy', 'watch']);
     grunt.registerTask('production', ['stylus', 'autoprefixer', 'uglify', 'cssmin']);

};