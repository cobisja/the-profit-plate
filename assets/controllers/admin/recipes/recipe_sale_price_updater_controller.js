import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
  static values = {
    url: String,
  };

  static targets = [
    "salePrice",
    "numberOfServings",
    "expensesPercentage",
    "profitPercentage",
    "totalCosts",
  ];

  async updateSalePrice(event) {
    let options = {
      number_of_servings: $(this.numberOfServingsTarget).val(),
      expenses_percentage: $(this.expensesPercentageTarget).val(),
      profit_percentage: $(this.profitPercentageTarget).val(),
      ingredient_costs: this.totalCostsTarget.innerText,
    };

    await $.get(this.urlValue, options)
      .then((response) => {
        this.salePriceTarget.innerText = response.data.sale_price.toFixed(2);
      })
      .catch(() => (this.salePriceTarget.innerText = "0.00"));
  }
}
