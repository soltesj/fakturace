import {Controller} from "@hotwired/stimulus";
import {Chart, registerables} from "chart.js";

Chart.register(...registerables);

export default class extends Controller {
    static values = {
        type: String,
        data: Object,
        options: Object,
    }

    connect() {
        console.log("Chart controller připojen");
        new Chart(this.element, {
            type: this.typeValue || 'bar',
            data: this.dataValue,
            options: this.optionsValue || {}
        });
    }
}