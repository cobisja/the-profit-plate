import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["filterSelection"];
  applyFilter() {
    const selectedFilter = this.filterSelectionTargets.filter(
      (selection) => selection.checked,
    );

    if (0 === selectedFilter.length) {
      return;
    }

    this.dispatch("filter_applied", {
      detail: {
        selectedFilter: selectedFilter[0].value,
      },
    });
  }

  resetFilter() {
    this.dispatch("filter_reset");

    for (let filter of this.filterSelectionTargets) {
      filter.checked = false;
    }
  }
}
