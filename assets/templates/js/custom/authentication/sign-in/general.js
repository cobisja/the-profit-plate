/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => {
  // webpackBootstrap
  /******/ "use strict";
  /******/ var __webpack_modules__ = {
    /***/ "../demo1/src/js/custom/authentication/sign-in/general.js":
      /*!****************************************************************!*\
  !*** ../demo1/src/js/custom/authentication/sign-in/general.js ***!
  \****************************************************************/
      /***/ () => {
        eval(
          "\n\n// Class definition\nvar KTSigninGeneral = function () {\n    // Elements\n    var form;\n    var submitButton;\n    var validator;\n\n    // Handle form\n    var handleValidation = function (e) {\n        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/\n        validator = FormValidation.formValidation(\n            form,\n            {\n                fields: {\n                    'email': {\n                        validators: {\n                            regexp: {\n                                regexp: /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/,\n                                message: 'The value is not a valid email address',\n                            },\n                            notEmpty: {\n                                message: 'Email address is required'\n                            }\n                        }\n                    },\n                    'password': {\n                        validators: {\n                            notEmpty: {\n                                message: 'The password is required'\n                            }\n                        }\n                    }\n                },\n                plugins: {\n                    trigger: new FormValidation.plugins.Trigger(),\n                    bootstrap: new FormValidation.plugins.Bootstrap5({\n                        rowSelector: '.fv-row',\n                        eleInvalidClass: '',  // comment to enable invalid state icons\n                        eleValidClass: '' // comment to enable valid state icons\n                    })\n                }\n            }\n        );\n    }\n\n    // Public functions\n    return {\n        // Initialization\n        init: function () {\n            form = document.querySelector('#kt_sign_in_form');\n            submitButton = document.querySelector('#kt_sign_in_submit');\n\n            handleValidation();\n\n            \n        }\n    };\n}();\n\n// On document ready\nKTUtil.onDOMContentLoaded(function () {\n    KTSigninGeneral.init();\n});\n\n\n//# sourceURL=webpack://keenthemes/../demo1/src/js/custom/authentication/sign-in/general.js?",
        );

        /***/
      },

    /******/
  };
  /************************************************************************/
  /******/
  /******/ // startup
  /******/ // Load entry module and return exports
  /******/ // This entry module can't be inlined because the eval devtool is used.
  /******/ var __webpack_exports__ = {};
  /******/ __webpack_modules__[
    "../demo1/src/js/custom/authentication/sign-in/general.js"
  ]();
  /******/
  /******/
})();
