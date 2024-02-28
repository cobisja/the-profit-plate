import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

export default class extends Controller {
  static values = {
    reloadContentUrl: String,
  };

  static targets = ["updatableContent", "modalForm", "modalBody"];

  modal = null;

  async openModal(event) {
    event.preventDefault();

    console.log($(event.currentTarget).attr("href"));

    this.modalBodyTarget.innerHTML = "Loading...";
    this.modal = new Modal(this.modalFormTarget);

    this.modal.show();
    this.modalBodyTarget.innerHTML = await $.ajax(
      $(event.currentTarget).attr("href"),
    );
  }

  async submitForm(event) {
    console.log(event);
    event.preventDefault();

    const form = $(this.modalBodyTarget).find("form");

    await $.ajax({
      url: form.prop("action"),
      method: form.prop("method"),
      data: form.serialize(),
    })
      .then(() => this.closeModal())
      .catch((err) => (this.modalBodyTarget.innerHTML = err.responseText));
  }

  async closeModal() {
    await $.get(this.reloadContentUrlValue).then((response) => {
      this.modal.hide();
      this.updatableContentTarget.innerHTML = response;
    });
  }
}
