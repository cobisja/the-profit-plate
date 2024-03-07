import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  static values = {
    fullReloadRequired: { type: Boolean, default: false },
  };

  static targets = ["currentRow", "updatableContent", "modalForm", "modalBody"];

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

    /**
     * No DataTables, requires render the whole tbody content
     */
    if (this.fullReloadRequiredValue) {
      options.headers = { "full-reload": 1 };
    }

    await $.ajax(options)
      .then((response) => {
        this.modal.hide();

        !this.fullReloadRequiredValue
          ? this.addOrUpdateRow(response)
          : this.redrawTableBody(response);

        Swal.fire({ text: "The task was completed!", icon: "success" });
      })
      .catch((err) => (this.modalBodyTarget.innerHTML = err.responseText));
  }

  addOrUpdateRow(response) {
    let table = $("table").DataTable();
    let row = table.row(this.currentRowIndex);

    if (-1 !== this.currentRowIndex) {
      row.data(response).draw(false);
    } else {
      table.row.add(response).draw();
    }
  }

  redrawTableBody(response) {
    this.updatableContentTarget.innerHTML = response;
  }
}
