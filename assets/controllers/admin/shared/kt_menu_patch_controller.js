import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  connect() {
    /**
     * Recreates all menus after Ajax calls (e.g. Twig Live components)
     */
    window.KTMenu.createInstances();
  }
}
