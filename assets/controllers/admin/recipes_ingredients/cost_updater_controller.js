import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
  static values = {
    url: String,
  };

  static targets = ["product", "quantity", "unit", "cost"];

  async connect() {
    await this.recalculateCost();
  }

  disconnect() {
    this.notifyCollectionUpdated();
  }

  async recalculateCost() {
    const productId = $(this.productTarget).val();
    const quantity = $(this.quantityTarget).val() || 0;
    const unit = $(this.unitTarget).val();

    if (!productId || !quantity || !unit) {
      this.costTarget.innerText = 0;

      return;
    }

    await $.post(this.urlValue, {
      product_id: productId,
      quantity: quantity,
      unit: unit,
    })
      .then((response) => {
        this.costTarget.innerText = response.data.cost.toFixed(2);

        this.notifyCollectionUpdated();
      })
      .catch(() => (this.costTarget.innerText = 0));
  }

  notifyCollectionUpdated() {
    this.dispatch("ingredients_collection:updated", {
      prefix: false,
    });
  }
}
