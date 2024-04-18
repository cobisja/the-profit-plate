import { Controller } from "@hotwired/stimulus";
import { getComponent } from "@symfony/ux-live-component";

export default class extends Controller {
  async initialize() {
    this.component = await getComponent(this.element);
  }

  applyFilter(event) {
    const filterApplied = event.detail.selectedFilter;

    this.component.set("recipeTypeName", filterApplied);
    this.component.render();
  }

  resetFilter() {
    this.component.set("recipeTypeName", "");
    this.component.render();
  }
}
