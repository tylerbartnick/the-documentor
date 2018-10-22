var gulp = require('gulp');
var cleanCSS = require('gulp-clean-css');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

// Copy vendor libraries from /node_modules into /vendor
gulp.task('copy-vendor', function () {
  gulp.src(['node_modules/bootstrap/dist/**/*', '!**/npm.js', '!**/bootstrap-theme.*', '!**/*.map'])
    .pipe(gulp.dest('src/public/vendor/bootstrap'));

  gulp.src([
    'node_modules/font-awesome/**',
    '!node_modules/font-awesome/**/*.map',
    '!node_modules/font-awesome/.npmignore',
    '!node_modules/font-awesome/*.txt',
    '!node_modules/font-awesome/*.md',
    '!node_modules/font-awesome/*.json'
  ]).pipe(gulp.dest('src/public/vendor/font-awesome'));

  gulp.src(['node_modules/jquery/dist/jquery.js', 'node_modules/jquery/dist/jquery.min.js'])
    .pipe(gulp.dest('src/public/vendor/jquery'));

  gulp.src(['node_modules/showdown/dist/showdown.min.js'])
    .pipe(gulp.dest('src/public/vendor/showdown'));
});

// Minify CSS
gulp.task('minify-css', [], function () {
  return gulp.src('src/public/css/*.css')
    .pipe(cleanCSS({ compatibility: 'ie8' }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('src/public/css/min'));
});

// Minify JS
gulp.task('minify-js', function () {
  return gulp.src('src/public/js/*.js')
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('src/public/js/min'));
});

gulp.task('dev', ['copy-vendor', 'minify-css', 'minify-js'], function () {
  gulp.watch('src/public/css/**/*.css', ['minify-css']);
  gulp.watch('src/public/js/*.js', ['minify-js']);
});

// Run everything
gulp.task('default', ['copy-vendor', 'minify-css', 'minify-js', 'dev']);
