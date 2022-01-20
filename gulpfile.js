(function(){
   'use strict';

   const gulp = require( 'gulp' );
   const sass = require( 'gulp-sass' )( require( 'sass' ) );
   const cleanCSS = require( 'gulp-clean-css' );
   const rename = require( 'gulp-rename' );

   const autoprefixer = require( 'gulp-autoprefixer' );
   const sassLint = require( 'gulp-sass-lint' );

   function processSCSS() {
       return gulp.src( './public/assets/src/scss/**/*.scss' )
           .pipe( sassLint( {
                'rules': {

                }
           } ) )
           .pipe( sassLint.format() )
           .pipe( sassLint.failOnError() )
           .pipe( sass().on( 'error', sass.logError ) )
           .pipe( autoprefixer( {
              'overrideBrowserlist': [
                  'last 2 versions'
              ]
           } ) )
           .pipe( gulp.dest( './public/assets/dist/css' ) )
           .pipe( cleanCSS() )
           .pipe( rename( { suffix: '.min' } ) )
           .pipe( gulp.dest( './public/assets/dist/css' ) );
   }

   exports.default = processSCSS;
   exports.watch = gulp.watch( [ './public/assets/src/scss/**/*.scss' ], processSCSS );

})();