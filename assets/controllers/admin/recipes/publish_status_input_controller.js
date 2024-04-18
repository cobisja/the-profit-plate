import { Controller } from "@hotwired/stimulus";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  static values = {
    url: String,
    csrfTokenId: String,
  };

  async updatePublishStatus() {
    const publishStatus = $(this.element).is(":checked");

    Swal.fire({
      title: "Recipe Publish status",
      text: `Are you sure to ${
        publishStatus ? "Publish" : "Unpublish"
      } the recipe?`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then(async (result) => {
      if (result.isConfirmed) {
        await $.post(this.urlValue, {
          _method: "patch",
          _csrf_token: this.csrfTokenIdValue,
          published_status: publishStatus,
        })
          .then(() => {
            Swal.fire({
              title: "Task completed",
              text: `The recipe has been ${
                publishStatus ? "Published" : "Unpublished"
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
      } else {
        $(this.element).prop("checked", !publishStatus);
      }
    });
  }
}
