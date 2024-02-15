import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['quantity', 'price', 'priceItem', 'vat', 'isPriceWithVat', 'priceTotal', 'priceTotalVat', 'priceVat']

    static values = {
        vats: Object,
    }
    priceTotal = 0;
    priceTotalVat = 0;

    connect() {
        this.calc()
    }

    calc() {
        this.priceTotalVat = 0;
        this.priceTotal = 0
        for (let i = 0; i < this.priceTargets.length; i++) {
            const vat = 1 + this.vatsValue[this.vatTargets[i].value] / 100;
            const price = parseFloat(this.priceTargets[i].value)
            const quantity = parseFloat(this.quantityTargets[i].value)
            const priceItem = price * quantity;
            const priceItemVat = priceItem * vat;
            if (priceItem * 1 === priceItem) this.priceTotal += priceItem;
            if (priceItemVat * 1 === priceItemVat) {
                this.priceTotalVat += priceItemVat;
                this.priceItemTargets[i].innerHTML = Math.round((priceItemVat + Number.EPSILON) * 100) / 100
            }
        }

        this.priceTotalTarget.innerHTML = Math.round((this.priceTotal + Number.EPSILON) * 100) / 100
        this.priceVatTarget.innerHTML = Math.round(((this.priceTotalVat - this.priceTotal) + Number.EPSILON) * 100) / 100
        this.priceTotalVatTarget.innerHTML = Math.round(this.priceTotalVat )
    }
}