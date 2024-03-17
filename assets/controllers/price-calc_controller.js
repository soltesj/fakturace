import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [
        'quantity',
        'price',
        'priceItem',
        'vat',
        'isPriceWithVat',
        'priceTotal',
        'priceTotalVat',
        'priceVat',
        'priceWithoutLowVat',
        'priceWithoutHighVat',
        'priceTotalWithVat',
        'priceTotalWithoutVat',
    ]

    static values = {
        vats: Object,
    }
    priceTotalWithoutVat = [];
    priceTotalWithVat = 0;


    connect() {
        this.calc()
    }

    calc() {
        this.priceTotalWithVat = 0;
        this.priceTotalWithoutVat = [];
        for (let i = 0; i < this.priceTargets.length; i++) {
            // console.log(this.vatTargets[i].value);
            const vatIndex = parseInt(this.vatTargets[i].value);
            const vat = 1 + this.vatsValue[this.vatTargets[i].value] / 100;
            const price = parseFloat(this.priceTargets[i].value)
            const quantity = parseFloat(this.quantityTargets[i].value)
            const priceItem = price * quantity;
            const priceItemVat = priceItem * vat;

            if (this.priceTotalWithoutVat[vatIndex] === undefined) {
                this.priceTotalWithoutVat[vatIndex] = 0;
            }

            if (priceItem) {
                this.priceTotalWithoutVat[vatIndex] += priceItem;
            }
            if (priceItemVat) {
                this.priceTotalWithVat += priceItemVat;
                this.priceItemTargets[i].innerHTML = Math.round((priceItemVat + Number.EPSILON) * 100) / 100
            }
        }

        let sumWithoutVat = 0
        this.priceTotalWithoutVat.forEach((element) => {
            sumWithoutVat += element;
        })

        this.priceTotalTarget.innerHTML = Math.round((sumWithoutVat + Number.EPSILON) * 100) / 100
        this.priceVatTarget.innerHTML = Math.round(((this.priceTotalWithVat - sumWithoutVat) + Number.EPSILON) * 100) / 100
        this.priceTotalVatTarget.innerHTML = Math.round(this.priceTotalWithVat)


        if (this.priceTotalWithoutVat[9]) {
            this.priceWithoutHighVatTarget.value = Math.round((this.priceTotalWithoutVat[9] + Number.EPSILON) * 100) / 100
        }
        if (this.priceTotalWithoutVat[12]) {
            this.priceWithoutLowVatTarget.value = Math.round((this.priceTotalWithoutVat[12] + Number.EPSILON) * 100) / 100
        }

        this.priceTotalWithoutVatTarget.value = Math.round((sumWithoutVat + Number.EPSILON) * 100) / 100
        this.priceTotalWithVatTarget.value = Math.round(this.priceTotalWithVat)
    }
}