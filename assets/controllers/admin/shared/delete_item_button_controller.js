import { Controller } from "@hotwired/stimulus";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  static values = {
    redirectUrl: { type: String, default: null },
  };

  deleteItem(event) {
    const form = $(this.element);

    event.preventDefault();

    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then(async (result) => {
      if (result.isConfirmed) {
        await $.post(form.prop("action"), form.serialize())
          .then(() => {
            if (null === this.redirectUrlValue) {
              /**
               * Table redrawing requested!
               */
              this.dispatch("item-deleted", {
                detail: {
                  deletedRowIndex: form.closest("tr").data("row-index"),
                },
              });

              Swal.fire({
                title: "Deleted!",
                text: "The item has been deleted.",
                icon: "success",
              });
            } else {
              window.location = this.redirectUrlValue;
            }
          })
          .catch((err) => {
            Swal.fire({
              title: "An error has occurred",
              text: err.responseText,
              icon: "error",
            });
          });
      }
    });
  }
}
