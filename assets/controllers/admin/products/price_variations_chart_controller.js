import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  static values = {
    url: String,
  };

  static targets = ["chartModal", "chartContainer"];

  async showChart(event) {
    const chartModal = new Modal(this.chartModalTarget);

    await $.post(this.urlValue)
      .then((response) => {
        this.chartContainerTarget.innerHTML = response;

        chartModal.show();
      })
      .catch((err) =>
        Swal.fire({
          title: "An error has occurred",
          text: err.responseText,
          icon: "error",
        }),
      );
  }
}
