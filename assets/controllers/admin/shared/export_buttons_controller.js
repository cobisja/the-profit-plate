import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
  static targets = ["dtButtonsContainer"];

  export(event) {
    event.preventDefault();

    const exportFormat = event.target.getAttribute("data-export-format");
    const dtButton = $(this.dtButtonsContainerTarget).find(
      `button.dt-button.buttons-${exportFormat}`,
    );

    if (0 === dtButton.length) {
      return;
    }

    dtButton.click();
  }
}
