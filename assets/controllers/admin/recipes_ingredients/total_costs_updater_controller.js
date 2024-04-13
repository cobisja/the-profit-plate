import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["totalCosts", "ingredientCost"];

  updateTotalCost() {
    let totalCosts = 0;

    for (const ingredientCostTarget of this.ingredientCostTargets) {
      totalCosts += +ingredientCostTarget.innerText || 0;
    }

    this.totalCostsTarget.innerText = totalCosts.toFixed(2);

    this.dispatch("total-ingredient-costs:updated");
  }
}
