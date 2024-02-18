import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    isLoggedIn: Boolean,
  };

  static targets = ["submitButton"];

  connect() {
    this.submitButtonTarget.disabled = this.isLoggedInValue;
  }
}
