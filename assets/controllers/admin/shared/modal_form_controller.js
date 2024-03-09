import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  static targets = ["currentRow", "modalForm", "modalBody"];

  modal = null;
  currentRowIndex = null;

  async openModal(event) {
    event.preventDefault();

    await $.ajax($(event.currentTarget).attr("href"))
      .then((response) => {
        this.modalBodyTarget.innerHTML = "Loading...";
        this.modal = new Modal(this.modalFormTarget);
        this.currentRowIndex = $(event.target).closest("tr").data("row-index");
        this.modal.show();

        this.modalBodyTarget.innerHTML = response;
      })
      .catch((err) => {
        Swal.fire({
          title: "An error has occurred",
          text: err.responseText,
          icon: "error",
        });
      });
  }

  async submitForm(event) {
    event.preventDefault();

    const form = $(this.modalBodyTarget).find("form");

    let options = {
      url: form.prop("action"),
      method: form.prop("method"),
      data: form.serialize(),
    };

    await $.ajax(options)
      .then((response) => {
        this.modal.hide();

        if (-1 !== this.currentRowIndex) {
          this.dispatch("item-updated", {
            detail: {
              rowIndex: this.currentRowIndex,
              rowData: response,
            },
          });
        } else {
          this.dispatch("item-added", {
            detail: {
              rowData: response,
            },
          });
        }

        Swal.fire({ text: "The task was completed!", icon: "success" });
      })
      .catch((err) => (this.modalBodyTarget.innerHTML = err.responseText));
  }
}
