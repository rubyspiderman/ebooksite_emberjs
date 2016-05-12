module.exports = function(grunt) {
  grunt.initConfig({
    clean: {
      before: ['fonts', 'images'],
      after: ['build']
    },
    compass: {
      options: {
        config: 'config.rb'
      },
      build: {
        options: {
          force: true
        }
      }
    },
    copy: {
      build: {
        files: [
          {
            cwd: 'assets/',
            dest: 'build/',
            expand: true,
            src: ['**']
          },
          {
            cwd: 'assets/fonts/',
            dest: 'fonts/',
            expand: true,
            src: ['**']
          },
          {
            cwd: 'assets/images/',
            dest: 'images/',
            expand: true,
            src: ['**']
          }
        ]
      }
    },
    emberTemplates: {
      build: {
        options: {
          templateBasePath: /build\/templates\//,
          templateName: function(filename) {
            return filename.replace('.', '/');
          }
        },
        files: {
          'build/templates.js': ['build/templates/**/*.hbs']
        }
      }
    },
    uglify: {
      options: {
        beautify: {},
        compress: {},
        mangle: {}
      },
      build: {
        files: {
          'bundle.js': [
            'build/libraries/cldr/plurals.js',
            'build/libraries/jquery/dist/jquery.js',
            'build/libraries/handlebars/handlebars.js',
            'build/libraries/ember/ember.prod.js',
            'build/libraries/ember-data/ember-data.prod.js',
            'build/libraries/ember-i18n/lib/i18n.js',
            'build/libraries/ember-simple-auth/simple-auth.js',
            'build/libraries/ember-simple-auth/simple-auth-oauth2.js',
            'build/libraries/ember-simple-auth/simple-auth-cookie-store.js',
            'build/libraries/bootstrap/js/modal.js',
            'build/libraries/bootstrap/js/tooltip.js',
            'build/libraries/galleria/src/galleria.js',
            'build/libraries/galleria/src/themes/classic/galleria.classic.js',
            'build/libraries/html.sortable/dist/html.sortable.js',
            'build/libraries/jquery-guillotine/js/jquery.guillotine.js',
            'build/libraries/momentjs/moment.js',
            'build/libraries/pikaday/pikaday.js',
            'build/libraries/typeahead.js/dist/typeahead.jquery.js',
            'build/libraries/wysihtml5/dist/wysihtml5-0.4.0pre.js',
            'build/scripts/application.js',
            'build/scripts/**/*.js',
            'build/scripts/router.js',
            'build/templates.js'
          ],
          'en.js': [
            'build/languages/en.js'
          ],
          'fr.js': [
            'build/languages/fr.js'
          ]
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-ember-templates');

  grunt.registerTask(
    'default',
    'Compiles all assets.',
    ['clean:before', 'copy', 'emberTemplates', 'uglify', 'compass', 'clean:after']
  );
};
