import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./templates/css/style.bundle.css";
import $ from "jquery";

window.$ = $;

/**
 * Datatables dependencies
 */
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import "./custom-components/js/custom-components.js";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
