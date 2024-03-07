import { Controller } from "@hotwired/stimulus";
import Swal from "sweetalert2";
import $ from "jquery";

export default class extends Controller {
  showAlert(event) {
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
        const form = $(this.element);
        const table = $("table").DataTable();

        await $.post(form.prop("action"), form.serialize())
          .then(() => {
            table.row(form.closest("tr")).remove().draw();

            Swal.fire({
              title: "Deleted!",
              text: "The item has been deleted.",
              icon: "success",
            });
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
