import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
  static targets = [
    "salePrice",
    "numberOfServings",
    "expensesPercentage",
    "profitPercentage",
    "totalCosts",
  ];

  async updateSalePrice(event) {
    const numberOfServings = parseInt($(this.numberOfServingsTarget).val());
    const expensesPercentage = parseFloat(
      $(this.expensesPercentageTarget).val(),
    );
    const profitPercentage = parseFloat($(this.profitPercentageTarget).val());
    const ingredientCosts = parseFloat(this.totalCostsTarget.innerText);
    const profit = (ingredientCosts * profitPercentage) / 100;
    const expenses = profit + (profit * expensesPercentage) / 100;
    const salePrice = (ingredientCosts + profit + expenses) / numberOfServings;

    this.salePriceTarget.innerText = (
      !isNaN(salePrice) ? salePrice : 0
    ).toFixed(2);
  }
}
