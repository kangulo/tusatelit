
"use strict";

// Load plugins
const browserSync   = require('browser-sync');
const gulp          = require('gulp');
const sass          = require('gulp-sass');
const sourcemaps    = require('gulp-sourcemaps');
const autoprefixer  = require('gulp-autoprefixer');
const jshint        = require('gulp-jshint');
const path          = require('path');
const rename        = require('gulp-rename');

var src = {
    html: 'C:\\xampp_73\\htdocs\\rc\\wp-content\\themes\\responder-portal\\html/**/*.{js,html}',
    css: 'C:\\xampp_73\\htdocs\\rc\\wp-content\\themes\\responder-portal\\css/**/*.css',
    sass: 'C:\\xampp_73\\htdocs\\rc\\wp-content\\themes\\responder-portal\\sass/**/*.scss',
    js: 'C:\\xampp_73\\htdocs\\rc\\wp-content\\themes\\responder-portal\\js/**/*.js',
    images: 'C:\\xampp_73\\htdocs\\rc\\wp-content\\themes\\responder-portal\\images/**/*.{jpg,gif,svg,png}',
    fonts: 'C:\\xampp_73\\htdocs\\rc\\wp-content\\themes\\responder-portal\\fonts/**/*.{eot,ttf,woff,woff2,eof,svg}',
};

var dest = {
    html: './html',
    css: './css',
    sass: './sass',
    js: './js',
    images: './images',
    fonts: './fonts',
};

// Start browserSync server
gulp.task('browserSync', function() {
  browserSync.init({
    injectChanges: true,
    proxy: "http://localhost/tusatelit.com/"
  });
})

// BrowserSync Reload
gulp.task('browserSyncReload', function(done) {
  browserSync.reload();
  done();
})

// Compile SASS Files
gulp.task('sass', function() {
  return gulp.src('sass/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({
        outputStyle: 'expanded', // nested, compact, expanded, compressed
        precision: 10,
      }).on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: [                
        'last 5 versions'
      ]
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./'))    // Outputs it in the root folder
    .pipe(browserSync.stream());
})

// Compile SASS Files
gulp.task('icon_fonts', function() {
  return gulp.src('sass/icon_fonts.scss')
    .pipe(sass({
        outputStyle: 'expanded', // nested, compact, expanded, compressed
        precision: 10,
      }).on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: [                
        'last 5 versions'
      ]
    }))
    .pipe(gulp.dest('./css'))    // Outputs it in the root folder
    .pipe(browserSync.stream());
})

// Report JS Problems
gulp.task('lint', function() {
  return gulp.src('js/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
})

// Watch the files
gulp.task('watch', function() {
  gulp.watch('**/*.scss', gulp.series('sass'));
  gulp.watch('**/*.html', gulp.series('browserSyncReload'));
  gulp.watch('**/*.php', gulp.series('browserSyncReload'));
  gulp.watch('**/js/**/*.js', gulp.series('lint','browserSyncReload'));
})

// Get Files from rc 
gulp.task('getfiles', function(done) {

   gulp.src(src.html).pipe(gulp.dest(dest.html));
   gulp.src(src.css).pipe(gulp.dest(dest.css));
   gulp.src(src.sass).pipe(gulp.dest(dest.sass));
   gulp.src(src.js).pipe(gulp.dest(dest.js));
   gulp.src(src.images).pipe(gulp.dest(dest.images));
   gulp.src(src.fonts).pipe(gulp.dest(dest.fonts));
   done();
});


// Build Sequences
// ---------------
gulp.task('default', function(callback) {
  return gulp.series(gulp.parallel('sass'), gulp.parallel('watch', 'browserSync'))();
})