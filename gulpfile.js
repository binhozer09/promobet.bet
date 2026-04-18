const del = require('del');
const gulp = require('gulp');
const npmdist = require('gulp-npm-dist');
const replace = require('gulp-replace');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require("gulp-autoprefixer");
const sourcemaps = require("gulp-sourcemaps");
const cleanCSS = require('gulp-clean-css');
const newer = require("gulp-newer");


const paths = {
    base: {
        base: {
            dir: './'
        },
        node: {
            dir: './node_modules'
        },
        packageLock: {
            files: './package-lock.json'
        }
    },
    dist: {
        libs: {
            dir: "./dash/assets/libs",
        },
        images: {
            dir: "./dash/assets/images",
        },
        fonts: {
            dir: "./dash/assets/fonts",
        },
        data: {
            dir: './dash/assets/data',
        },
        css: {
            dir: "./dash/assets/css",
        },
        js: {
            dir: "./dash/assets/js",
            files: "./dash/assets/js/pages",
        },
    },
    src: {
        base: {
            dir: './src',
            files: './dash/**/*'
        },
        css: {
            dir: './dash/assets/source/css',
            files: './dash/assets/source/css/**/*'
        },
        html: {
            dir: './src',
            files: './dash/**/*.html',
        },
        images: {
            dir: './dash/assets/source/images',
            files: './dash/assets/source/images/**/*',
        },
        fonts: {
            dir: "./dash/assets/source/fonts",
            files: "./dash/assets/source/fonts/**/*",
        },
        data: {
            dir: './dash/assets/source/data',
            files: './dash/assets/source/data/**/*',
        },
        js: {
            dir: './dash/assets/source/js',
            pages: './dash/assets/source/js/pages',
            files: './dash/assets/source/js/pages/*.js',
            main: './dash/assets/source/js/*.js',
        },
        scss: {
            dir: './dash/assets/source/scss',
            files: './dash/assets/source/scss/**/*',
            icons: './dash/assets/source/scss/icons.scss',
            main: './dash/assets/source/scss/app**.scss',
            bootstrap: './dash/assets/source/scss/bootstrap**.scss'
        }
    }
};

gulp.task('browsersyncReload', function (callback) {
    browsersync.reload();
    callback();
});

gulp.task('watch', function () {
    gulp.watch(paths.src.js.dir, gulp.series('js'));
    gulp.watch(paths.src.js.pages, gulp.series('jsPages'));
    gulp.watch(paths.src.images.dir, gulp.series("images"));
    gulp.watch(paths.src.scss.icons, gulp.series('icons'));
    gulp.watch([paths.src.scss.bootstrap, '!' + paths.src.scss.main, '!' + paths.src.scss.icons], gulp.series('bootstrap', 'browsersyncReload'));
    gulp.watch([paths.src.scss.files, '!' + paths.src.scss.bootstrap, '!' + paths.src.scss.icons], gulp.series('scss', 'browsersyncReload'));
});

gulp.task('js', function () {
    return gulp
        .src(paths.src.js.main)
        .pipe(uglify())
        .pipe(gulp.dest(paths.dist.js.dir));
});

gulp.task('jsPages', function () {
    return gulp
        .src(paths.src.js.files)
        .pipe(uglify())
        .pipe(gulp.dest(paths.dist.js.files));
});

//  Compile app scss
gulp.task('scss', function () {
    // generate ltr  
    return gulp
        .src([paths.src.scss.main, '!' + paths.src.scss.bootstrap, '!' + paths.src.scss.icons])
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(
            autoprefixer()
        )
        .pipe(gulp.dest(paths.dist.css.dir))
        .pipe(cleanCSS())
        .pipe(
            rename({
                suffix: ".min"
            })
        )
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest(paths.dist.css.dir));


});

//  Compile bootstrap scss
gulp.task('bootstrap', function () {
    // generate ltr  
    return gulp
        .src([paths.src.scss.bootstrap, '!' + paths.src.scss.main, '!' + paths.src.scss.icons])
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(
            autoprefixer()
        )
        .pipe(gulp.dest(paths.dist.css.dir))
        .pipe(cleanCSS())
        .pipe(
            rename({
                suffix: ".min"
            })
        )
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest(paths.dist.css.dir));
});

//  Compile Icons
gulp.task('icons', function () {
    return gulp
        .src(paths.src.scss.icons)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(paths.dist.css.dir))
        .pipe(cleanCSS())
        .pipe(
            rename({
                suffix: ".min"
            })
        )
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest(paths.dist.css.dir));
});

gulp.task("images", function () {
    return gulp
        .src(paths.src.images.files)
        .pipe(newer(paths.dist.images.dir))
        .pipe(gulp.dest(paths.dist.images.dir));
});

gulp.task("fonts", function () {
    return gulp
        .src(paths.src.fonts.files)
        .pipe(newer(paths.dist.fonts.dir))
        .pipe(gulp.dest(paths.dist.fonts.dir));
});

gulp.task("data", function () {
    return gulp
        .src(paths.src.data.files)
        .pipe(newer(paths.dist.data.dir))
        .pipe(gulp.dest(paths.dist.data.dir));
});

//  Clean Dist
gulp.task('clean:dist', function (callback) {
    del.sync(paths.dist.base.dir);
    callback();
});

gulp.task('copy:libs', function () {
    return gulp
        .src(npmdist(), { base: paths.base.node.dir })
        .pipe(rename(function (path) {
            path.dirname = path.dirname.replace(/\/dist/, '').replace(/\\dist/, '');
        }))
        .pipe(gulp.dest(paths.dist.libs.dir));
});


//  Producation Task
gulp.task('default', gulp.series(gulp.parallel('copy:libs', 'scss', 'bootstrap', 'icons', 'js', 'jsPages', 'images', 'fonts','data'), gulp.parallel('watch')));

//  Build Task
gulp.task('build', gulp.series(gulp.parallel('copy:libs', 'scss', 'bootstrap', 'icons', 'js', 'jsPages', 'images', 'fonts','data')));
