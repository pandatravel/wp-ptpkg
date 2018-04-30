/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 280);
/******/ })
/************************************************************************/
/******/ ({

/***/ 280:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(281);
__webpack_require__(282);
__webpack_require__(285);
module.exports = __webpack_require__(286);


/***/ }),

/***/ 281:
/***/ (function(module, exports) {

(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(function () {
        // Uploading files
        var file_frame;

        $.fn.upload_banner_image = function (button) {
            var button_id = button.attr('id');
            var field_id = button_id.replace('_button', '');

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {
                    text: $(this).data('uploader_button_text')
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $("#" + field_id).val(attachment.id);
                $("#package-meta img").attr('src', attachment.url);
                $('#package-meta img').show();
                $('#' + button_id).attr('id', 'remove_banner_image_button');
                $('#remove_banner_image_button').text('Remove banner image');
            });

            // Finally, open the modal
            file_frame.open();
        };

        $('#package-meta').on('click', '#upload_banner_image_button', function (event) {
            event.preventDefault();
            $.fn.upload_banner_image($(this));
        });

        $('#package-meta').on('click', '#remove_banner_image_button', function (event) {
            event.preventDefault();
            $('#upload_banner_image').val('');
            $('#package-meta img').attr('src', '');
            $('#package-meta img').hide();
            $(this).attr('id', 'upload_banner_image_button');
            $('#upload_banner_image_button').text('Set banner image');
        });
    });
})(jQuery);

/***/ }),

/***/ 282:
/***/ (function(module, exports) {

throw new Error("Module build failed: ModuleBuildError: Module build failed: Error: Node Sass does not yet support your current environment: OS X 64-bit with Unsupported runtime (64)\nFor more information on which environments are supported please see:\nhttps://github.com/sass/node-sass/releases/tag/v4.7.2\n    at module.exports (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/node-sass/lib/binding.js:13:13)\n    at Object.<anonymous> (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/node-sass/lib/index.js:14:35)\n    at Module._compile (internal/modules/cjs/loader.js:678:30)\n    at Object.Module._extensions..js (internal/modules/cjs/loader.js:689:10)\n    at Module.load (internal/modules/cjs/loader.js:589:32)\n    at tryModuleLoad (internal/modules/cjs/loader.js:528:12)\n    at Function.Module._load (internal/modules/cjs/loader.js:520:3)\n    at Module.require (internal/modules/cjs/loader.js:626:17)\n    at require (internal/modules/cjs/helpers.js:20:18)\n    at Object.<anonymous> (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/sass-loader/lib/loader.js:3:14)\n    at Module._compile (internal/modules/cjs/loader.js:678:30)\n    at Object.Module._extensions..js (internal/modules/cjs/loader.js:689:10)\n    at Module.load (internal/modules/cjs/loader.js:589:32)\n    at tryModuleLoad (internal/modules/cjs/loader.js:528:12)\n    at Function.Module._load (internal/modules/cjs/loader.js:520:3)\n    at Module.require (internal/modules/cjs/loader.js:626:17)\n    at require (internal/modules/cjs/helpers.js:20:18)\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:13:17)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:362:2)\n    at NormalModule.doBuild (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:182:3)\n    at NormalModule.build (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:275:15)\n    at Compilation.buildModule (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:151:10)\n    at moduleFactory.create (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:454:10)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:195:19)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:364:11\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:170:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:27:11)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:362:2)\n    at NormalModule.doBuild (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:182:3)\n    at NormalModule.build (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:275:15)\n    at Compilation.buildModule (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:151:10)\n    at moduleFactory.create (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:454:10)\n    at factory (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:243:5)\n    at applyPluginsAsyncWaterfall (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:94:13)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/tapable/lib/Tapable.js:268:11\n    at NormalModuleFactory.params.normalModuleFactory.plugin (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/CompatibilityPlugin.js:52:5)\n    at NormalModuleFactory.applyPluginsAsyncWaterfall (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/tapable/lib/Tapable.js:272:13)\n    at resolver (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:69:10)\n    at process.nextTick (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:196:7)\n    at process._tickCallback (internal/process/next_tick.js:172:11)");

/***/ }),

/***/ 285:
/***/ (function(module, exports) {

throw new Error("Module build failed: ModuleBuildError: Module build failed: Error: Node Sass does not yet support your current environment: OS X 64-bit with Unsupported runtime (64)\nFor more information on which environments are supported please see:\nhttps://github.com/sass/node-sass/releases/tag/v4.7.2\n    at module.exports (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/node-sass/lib/binding.js:13:13)\n    at Object.<anonymous> (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/node-sass/lib/index.js:14:35)\n    at Module._compile (internal/modules/cjs/loader.js:678:30)\n    at Object.Module._extensions..js (internal/modules/cjs/loader.js:689:10)\n    at Module.load (internal/modules/cjs/loader.js:589:32)\n    at tryModuleLoad (internal/modules/cjs/loader.js:528:12)\n    at Function.Module._load (internal/modules/cjs/loader.js:520:3)\n    at Module.require (internal/modules/cjs/loader.js:626:17)\n    at require (internal/modules/cjs/helpers.js:20:18)\n    at Object.<anonymous> (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/sass-loader/lib/loader.js:3:14)\n    at Module._compile (internal/modules/cjs/loader.js:678:30)\n    at Object.Module._extensions..js (internal/modules/cjs/loader.js:689:10)\n    at Module.load (internal/modules/cjs/loader.js:589:32)\n    at tryModuleLoad (internal/modules/cjs/loader.js:528:12)\n    at Function.Module._load (internal/modules/cjs/loader.js:520:3)\n    at Module.require (internal/modules/cjs/loader.js:626:17)\n    at require (internal/modules/cjs/helpers.js:20:18)\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:13:17)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:362:2)\n    at NormalModule.doBuild (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:182:3)\n    at NormalModule.build (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:275:15)\n    at Compilation.buildModule (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:151:10)\n    at moduleFactory.create (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:454:10)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:195:19)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:364:11\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:170:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:27:11)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:362:2)\n    at NormalModule.doBuild (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:182:3)\n    at NormalModule.build (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:275:15)\n    at Compilation.buildModule (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:151:10)\n    at moduleFactory.create (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:454:10)\n    at factory (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:243:5)\n    at applyPluginsAsyncWaterfall (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:94:13)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/tapable/lib/Tapable.js:268:11\n    at NormalModuleFactory.params.normalModuleFactory.plugin (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/CompatibilityPlugin.js:52:5)\n    at NormalModuleFactory.applyPluginsAsyncWaterfall (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/tapable/lib/Tapable.js:272:13)\n    at resolver (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:69:10)\n    at process.nextTick (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:196:7)\n    at process._tickCallback (internal/process/next_tick.js:172:11)");

/***/ }),

/***/ 286:
/***/ (function(module, exports) {

throw new Error("Module build failed: ModuleBuildError: Module build failed: Error: Node Sass does not yet support your current environment: OS X 64-bit with Unsupported runtime (64)\nFor more information on which environments are supported please see:\nhttps://github.com/sass/node-sass/releases/tag/v4.7.2\n    at module.exports (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/node-sass/lib/binding.js:13:13)\n    at Object.<anonymous> (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/node-sass/lib/index.js:14:35)\n    at Module._compile (internal/modules/cjs/loader.js:678:30)\n    at Object.Module._extensions..js (internal/modules/cjs/loader.js:689:10)\n    at Module.load (internal/modules/cjs/loader.js:589:32)\n    at tryModuleLoad (internal/modules/cjs/loader.js:528:12)\n    at Function.Module._load (internal/modules/cjs/loader.js:520:3)\n    at Module.require (internal/modules/cjs/loader.js:626:17)\n    at require (internal/modules/cjs/helpers.js:20:18)\n    at Object.<anonymous> (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/sass-loader/lib/loader.js:3:14)\n    at Module._compile (internal/modules/cjs/loader.js:678:30)\n    at Object.Module._extensions..js (internal/modules/cjs/loader.js:689:10)\n    at Module.load (internal/modules/cjs/loader.js:589:32)\n    at tryModuleLoad (internal/modules/cjs/loader.js:528:12)\n    at Function.Module._load (internal/modules/cjs/loader.js:520:3)\n    at Module.require (internal/modules/cjs/loader.js:626:17)\n    at require (internal/modules/cjs/helpers.js:20:18)\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:13:17)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:362:2)\n    at NormalModule.doBuild (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:182:3)\n    at NormalModule.build (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:275:15)\n    at Compilation.buildModule (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:151:10)\n    at moduleFactory.create (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:454:10)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:195:19)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:364:11\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:170:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:27:11)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:165:10)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:173:18\n    at loadLoader (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/loadLoader.js:36:3)\n    at iteratePitchingLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:169:2)\n    at runLoaders (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/loader-runner/lib/LoaderRunner.js:362:2)\n    at NormalModule.doBuild (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:182:3)\n    at NormalModule.build (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModule.js:275:15)\n    at Compilation.buildModule (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:151:10)\n    at moduleFactory.create (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/Compilation.js:454:10)\n    at factory (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:243:5)\n    at applyPluginsAsyncWaterfall (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:94:13)\n    at /Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/tapable/lib/Tapable.js:268:11\n    at NormalModuleFactory.params.normalModuleFactory.plugin (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/CompatibilityPlugin.js:52:5)\n    at NormalModuleFactory.applyPluginsAsyncWaterfall (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/tapable/lib/Tapable.js:272:13)\n    at resolver (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:69:10)\n    at process.nextTick (/Library/WebServer/Documents/panda/pandaonline/wp-content/plugins/wp-ptpkg/node_modules/webpack/lib/NormalModuleFactory.js:196:7)\n    at process._tickCallback (internal/process/next_tick.js:172:11)");

/***/ })

/******/ });