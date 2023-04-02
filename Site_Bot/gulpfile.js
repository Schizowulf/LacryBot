'use strict';
var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var concat = require('gulp-concat');
sass.compiler = require('node-sass');
gulp.task('sass', function(){
    return gulp.src('resources/sass/*.scss')
      .pipe(sass({outputStyle: 'compressed'})) // Converts Sass to CSS with gulp-sass
      .pipe(gulp.dest('public/css'))
});