import "./bootstrap.js";
/**
 * Base theme styles
 */
import "./templates/plugins/global/plugins.bundle.css";
import "./templates/css/style.bundle.css";

/**
 * Custom styles
 */
import "./styles/admin.css";

/**
 * Base theme dependencies
 */
import $ from "jquery";
window.$ = $;

/**
 * Datatables
 */
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
