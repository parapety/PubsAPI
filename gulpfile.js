var gulp = require('gulp');
var less = require('gulp-less');

gulp.task('less', function () {
    gulp.src('./app/Resources/less/*.less')
        .pipe(less())
        .on('error', function(err){ console.log(err.message); })
        .pipe(gulp.dest('./web/css'));
});

gulp.task('default', ['less']);
var watcher = gulp.watch(['./app/Resources/less/*.less'], ['less']);
watcher.on('change',function (e) {
    console.log(e);
}).on('error', function (e) {
    console.log('ERROR: ' + e);
});
