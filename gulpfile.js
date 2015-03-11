/*PERSONNAL GULPFILE*/

/*
*   ** TODO **
*   problème autoprefixer et sitemaps
*
*/

/*jslint browser: true, devel: true */

// node charge les modules via require

// GENERAL
var gulp = require('gulp'),
    rename = require('gulp-rename'),
    sourcemaps = require('gulp-sourcemaps'),
    livereload = require('gulp-livereload'),
    plumber = require('gulp-plumber');

// CSS
var sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer');

// JS
var  uglify = require('gulp-uglify');

// IMAGES
var pngquant = require('gulp-imagemin'),
    imagemin = require('imagemin-pngquant');

/* DEFAULT GULP TASK */
gulp.task('default',
  //lance toutes les tâches une fois puis démarre l'écoute
  [
    'scripts',
    'styles2',
    'static',
    'php',
    'watch'
  ],
  function() {
});

/* REFRESH WHEN STATIC FILES CHANGE */
gulp.task('static', function() {
  gulp.src('dev/**/*.html', { base: 'dev/'})
    .pipe(gulp.dest('prod/'));
    livereload.reload();
});

/* REFRESH WHEN PHP FILES CHANGE */
gulp.task('php', function() {
  gulp.src('dev/**/*.php', { base: 'dev/'})
    .pipe(gulp.dest('prod/'));
    livereload.reload();
});

/* MINIFYING FILES + WRITING SOURCEMAPS + LAUNCHING LIVE RELOAD */
gulp.task('scripts', function() {
  //choisit fichier main.js
  gulp.src('dev/js/*.js')
    //initialise le sourcemaps
    .pipe(sourcemaps.init())
    .pipe(plumber())
    //minifie
    .pipe(uglify())
    .pipe(rename({
      // ajoute .min à la fin du nom de fichier (rename via hash, infos: https://www.npmjs.com/package/gulp-rename)
      suffix: '.min'
    }))
    //écriture des sourcemaps dans le même dossier que le .min
    .pipe(sourcemaps.write(
      './',
      {
        includeContent: false,            // par déf, le code source est copié dans le fichier .map (ici, non)
        sourceRoot: '../../dev/js/'       // spécifie l'emplacement des fichiers source par rapport au fichier map
      }))
    //dossier de sortie
    .pipe(gulp.dest('prod/js/'))
    //refresh with livereload
    .pipe(livereload());
});


/*
// Sass compilation + autoprefixer
gulp.task('styles', function () {
  return gulp.src(
    'dev/scss/main.scss',
    {
      sourcemap: true,
      style: 'compressed'
    })
    .pipe(plumber())
    .pipe(autoprefixer(
      'last 2 versions',
      '> 5%',
      'ie >= 9',
      'Firefox ESR'
    ))
  .pipe(sourcemaps.write(
    './',
    {
      includeContent: false,
      sourceRoot: '../scss/'
    }))
  .pipe(gulp.dest('css/'))
  .pipe(livereload());
});
*/

/*STYLES 2 - Styles modified, trying to correct bug with sourcemaps*/

gulp.task('styles2', function() {
    return sass(
      'dev/scss/main.scss',
      {
        // sourcemap: true, // currently, gulp-ruby-sass doesn't support external sourcemap
        style: 'compressed' // outputed css style
      })
      .pipe(sourcemaps.init())
      .pipe(autoprefixer())
    .on('error', function (err) {
      console.error('Error!', err.message);
   })
    // currently, gulp-ruby-sass doesn't support external sourcemap
//    .pipe(sourcemaps.write(
//      './',
//      {
//        includeContent: false,
//        sourceRoot: '../../dev/scss/' // spécifie le fichier source par rapport à l'endroit du fichier map
//    }))
    .pipe(gulp.dest('prod/css/'))
    .pipe(livereload());
});


/* IMAGES MINIFICATION and putting them into prod */
// gulp.task('imagemin', function() {
//     return gulp.src('dev/img/*')
//         .pipe(imagemin({
//             progressive: true,
//             svgoPlugins: [{removeViewBox: false}],
//             use: [pngquant()]
//         }))
//         .pipe(gulp.dest('prod/img'));
// });

/* WATCHING FOR CHANGES */
gulp.task('watch', function(){
  //démarre server livereload
  livereload.listen();
  // à chaque fois qu'il y a un chgt du fichier, lancer tâche
  gulp.watch('dev/js/*.js', ['scripts']);
  gulp.watch('dev/scss/**/*.scss', ['styles2']);
  gulp.watch('dev/*.html', ['static']);
  gulp.watch('dev/**/*.php', ['php']);
});