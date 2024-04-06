import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";
import $ from "jquery";

export default class extends Controller {
  static values = {
    imageSelector: { type: String, default: "img.zoom-image" },
    imageSourceProperty: { type: String, default: "src" },
    imageAltText: { type: String, default: "image" },
  };

  static targets = ["imageContainer", "modalTitle", "zoomModal"];

  zoomImage(event) {
    const imageElement = $(event.currentTarget).find(this.imageSelectorValue);

    if (!imageElement.length) {
      return;
    }

    const zoomModal = new Modal(this.zoomModalTarget);
    const imageSrc = imageElement.attr(this.imageSourcePropertyValue);
    const imageAlt = imageElement.attr("alt") || this.imageAltTextValue;

    this.modalTitleTarget.innerText = imageAlt.toUpperCase();
    this.imageContainerTarget.innerHTML = `<img style="height:auto;max-width:100%;" src="${imageSrc}" alt="${imageAlt}"/>`;

    zoomModal.show();
  }
}
