import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    isLoggedIn: Boolean,
  };

  static targets = ["submitButton"];

  connect() {
    this.submitButtonTarget.disabled = this.isLoggedInValue;
  }
}
