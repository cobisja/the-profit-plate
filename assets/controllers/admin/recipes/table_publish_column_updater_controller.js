import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
  static targets = ["publishStatus"];

  updatePublishStatus(event) {
    const publishStatus = event.detail.new_status;

    $(this.publishStatusTarget)
      .removeClass("badge-success badge-light-primary")
      .addClass(!publishStatus ? "badge-success" : "badge-light-primary");
    $(this.publishStatusTarget).text(!publishStatus ? "Published" : "Draft");
  }
}
