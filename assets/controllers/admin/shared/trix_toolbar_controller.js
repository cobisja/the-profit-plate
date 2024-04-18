import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
  static values = {
    showExtendedToolbar: { type: Boolean, default: false },
  };

  connect() {
    if (this.showExtendedToolbarValue) {
      this.element.addEventListener("trix-initialize", function (event) {
        const toolbar = event.target.toolbarElement;

        $(toolbar)
          .find(
            "button.trix-button:not(.trix-button-group--file-tools,.trix-button--icon-attach,.trix-button--icon-quote,.trix-button--icon-code)",
          )
          .show();
      });
    }
  }
}
