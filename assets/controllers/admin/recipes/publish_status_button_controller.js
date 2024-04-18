import { Controller } from "@hotwired/stimulus";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  static values = {
    publishedStatus: Boolean,
    csrfTokenId: String,
  };

  async updatePublishStatus(event) {
    event.preventDefault();

    const publishStatus = this.publishedStatusValue;
    const button = $(this.element);
    const url = button.attr("href");

    Swal.fire({
      title: "Recipe Publish status",
      text: `Are you sure to ${
        !publishStatus ? "Publish" : "Unpublish"
      } the recipe?`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then(async (result) => {
      if (result.isConfirmed) {
        await $.post(url, {
          _method: "patch",
          _csrf_token: this.csrfTokenIdValue,
          published_status: !publishStatus,
        })
          .then(() => {
            this.publishedStatusValue = !publishStatus;
            this.dispatch("publish_status_updated", {
              detail: {
                new_status: publishStatus,
              },
            });
            button.text(this.publishedStatusValue ? "Unpublish" : "Publish");

            Swal.fire({
              title: "Task completed",
              text: `The recipe has been ${
                this.publishedStatusValue ? "Published" : "Unpublished"
              }`,
              icon: "success",
            });
          })
          .catch((err) => {
            if (401 === err.status) {
              window.location.href = "/logout";

              return;
            }

            Swal.fire({
              title: "An error has occurred",
              text: err.responseJSON.error,
              icon: "error",
            });
          });
      }
    });
  }
}
