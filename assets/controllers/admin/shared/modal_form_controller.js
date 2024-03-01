import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

export default class extends Controller {
  static values = {
    reloadContentUrl: String,
    editAction: Boolean,
  };

  static targets = ["currentRow", "updatableContent", "modalForm", "modalBody"];

  modal = null;
  currentRowIndex = null;

  async openModal(event) {
    event.preventDefault();

    this.modalBodyTarget.innerHTML = "Loading...";
    this.modal = new Modal(this.modalFormTarget);
    this.currentRowIndex = $(event.currentTarget)
      .closest("tr")
      .data("row-index");

    this.modal.show();

    this.modalBodyTarget.innerHTML = await $.ajax(
      $(event.currentTarget).attr("href"),
    );
  }

  async submitForm(event) {
    event.preventDefault();

    const form = $(this.modalBodyTarget).find("form");

    await $.ajax({
      url: form.prop("action"),
      method: form.prop("method"),
      data: form.serialize(),
    })
      .then((response) => {
        this.modal.hide();

        let table = $("table").DataTable();
        let row = table.row(this.currentRowIndex);

        -1 !== this.currentRowIndex
          ? row.data(response).draw(false)
          : table.row.add(response).draw();
      })
      .catch((err) => (this.modalBodyTarget.innerHTML = err.responseText));
  }
}
