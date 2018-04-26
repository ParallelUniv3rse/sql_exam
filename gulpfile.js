const path = require('path');
const gulp = require('gulp');
const GulpClass = require('classy-gulp');
const config = require('./config/general.config');

/**
 * Gulp plugins starting with "gulp-<name>" are loaded automatically under gPlugins.<name>
 *     You can rename them or call functions on required plugins via options object passed to gulp-load-plugins:
 *     {
 *     rename: {},
 *     postRequireTransforms: {}
 *     }
 * Others are manually appended via the second array.
 */
const gPlugins = {
  ...require('gulp-load-plugins')(),
  ...{
    browserSync: require('browser-sync').create(),
  },
};

class Flow extends GulpClass {
  constructor() {
    super();
  }

  defineTasks() {
    /**
     * All tasks which are accessible via "gulp <taskName>" are defined here.
     */
    return {
      server: gulp.series(this.startBrowserSync),
      build: gulp.parallel(this.styles),
      default: gulp.series('build', 'server', this.watch),
    };
  }

  /**
   * Compiles general project styles. Vue component-specific styles are handled during js compilation.
   */
  styles() {
    return gulp.src([path.join(config.paths.sass.src, '/**/*.scss')])
      .pipe(gPlugins.plumber())
      .pipe(gPlugins.sourcemaps.init())
      .pipe(gPlugins.sass(require('./config/nodesass.config')).on('error', gPlugins.sass.logError))
      .pipe(gPlugins.autoprefixer({ browsers: ['last 3 versions', '> 1%'] }))
      .pipe(gPlugins.sourcemaps.write('.'))
      .pipe(gulp.dest(config.paths.sass.dist))
      .pipe(gPlugins.browserSync.stream({ match: '**/*.{css|map}' }));
  }

  /**
   * Watches for changes and automatically performs a given task depending on the type of file changed.
   */
  watch(done) {
    gulp.watch(path.join(config.paths.sass.src, '/**/*.scss'), gulp.series(this.styles, this.reload)); // SCSS recompile & reload
    gulp.watch(['app/**/*.php','*.php'], gulp.series(this.reload)); // PHP files change
    gulp.watch(path.join(config.paths.js.src, '**/*.js'), gulp.series(this.reload)); // frontend JS
    done();
  }

  /**
   *      ========= "Utility" classes start =========
   */


  /**
   *      ========= BrowserSync =========
   */

  /**
   * Starts the browsersync proxy server
   */
  startBrowserSync(done) {
    gPlugins.browserSync.init(require('./config/browsersync.config'), done);
  }

  /**
   * Reloads the whole page via browsersync
   */
  reload(done) {
    gPlugins.browserSync.reload();
    done();
  }
}

/**
 *      Let's get the party started!
 *      Don't forget to have fun on this new project! (✿ ◕ ‿ ◕)ᓄ ╰U╯
 */
gulp.registry(new Flow());
