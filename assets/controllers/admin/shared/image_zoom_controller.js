import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";
import $ from "jquery";

export default class extends Controller {
  static targets = ["imageContainer", "modalTitle", "zoomModal"];

  zoomImage(event) {
    const imageElement = $(event.currentTarget).find("img.zoom-image");

    if (!imageElement.length) {
      return;
    }

    const zoomModal = new Modal(this.zoomModalTarget);
    const imageSrc = imageElement.attr("src");
    const imageAlt = imageElement.attr("alt") || "recipe image";

    this.modalTitleTarget.innerText = imageAlt.toUpperCase();
    this.imageContainerTarget.innerHTML = `<img style="height:auto;max-width:100%;" src="${imageSrc}" alt="${imageAlt}"/>`;

    zoomModal.show();
  }
}
