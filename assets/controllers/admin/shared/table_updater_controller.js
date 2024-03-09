import { Controller } from "@hotwired/stimulus";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  static values = {
    fullReloadUrl: String,
    tableSelector: { type: String, default: "table" },
  };

  static targets = ["updatableContent"];

  async addRow(event) {
    const rowData = event.detail.rowData;

    "" === this.fullReloadUrlValue
      ? $(this.tableSelectorValue).DataTable().row.add(rowData).draw()
      : await this.reloadTableContent();
  }

  async updateRow(event) {
    const rowIndex = event.detail.rowIndex;
    const rowData = event.detail.rowData;

    "" === this.fullReloadUrlValue
      ? $(this.tableSelectorValue)
          .DataTable()
          .row(rowIndex)
          .data(rowData)
          .draw(false)
      : await this.reloadTableContent();
  }

  async deleteRow(event) {
    const deletedRowIndex = event.detail.deletedRowIndex;

    "" === this.fullReloadUrlValue
      ? $(this.tableSelectorValue)
          .DataTable()
          .row(deletedRowIndex)
          .remove()
          .draw()
      : await this.reloadTableContent();
  }

  async reloadTableContent() {
    await $.get(this.fullReloadUrlValue)
      .then((response) => (this.updatableContentTarget.innerHTML = response))
      .catch((err) => {
        Swal.fire({
          title: "An error has occurred",
          text: err.responseText,
          icon: "error",
        });
      });
  }
}
