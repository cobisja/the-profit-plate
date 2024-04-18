import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["recipesCount", "recipeCard"];

  updateCounter() {
    this.recipesCountTarget.innerText = this.recipeCardTargets.length;
  }
}
