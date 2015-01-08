var gulp    = require('gulp');
var phpunit = require('gulp-phpunit');

var paths = {
    tests: './tests/**/*Test.php'
};

gulp.task('watch', function () {
    gulp.watch(paths.tests).on('change', function (event) {
        gulp.src('phpunit.xml.dist')
            .pipe(phpunit('phpunit', {testClass: event.path}))
            .on('error', console.error);
    });
});

gulp.task('test', function () {
    return gulp.src('phpunit.xml.dist').pipe(phpunit('phpunit'))
});

