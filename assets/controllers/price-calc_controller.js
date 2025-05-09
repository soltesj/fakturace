import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['quantity', 'price', 'priceItem', 'vatPercentage', 'isPriceWithVat', 'priceTotal', 'priceTotalVat', 'priceVat', 'priceWithoutLowVat', 'priceWithoutHighVat', 'priceTotalWithVat', 'priceTotalWithoutVat', 'customer', 'documentWithVat', 'itemName', 'useDomesticReverseCharge']

    static values = {
        vats: Object, vatMode: String,
    }
    prices = [];
    priceTotalWithoutVat = [];
    priceTotal = 0;


    connect() {
        this.updateVatOptions()
        this.toggleDocumentWithWat()
        this.calc()
    }

    calc() {
        this.priceTotal = 0;
        this.priceTotalWithoutVat = [];
        this.prices = [];
        for (let i = 0; i < this.priceTargets.length; i++) {
            const price = parseFloat(this.priceTargets[i].value)
            const quantity = parseFloat(this.quantityTargets[i].value)
            const priceItem = price * quantity;
            if (this.isVatModeEnabled()) {
                const vat = 1 + this.vatsValue[this.vatPercentageTargets[i].value] / 100;
                const priceItemWithVat = priceItem * vat;
                this.priceTotal += priceItemWithVat ? priceItemWithVat : 0;
                this.priceItemTargets[i].innerHTML = priceItemWithVat ? this.round(priceItemWithVat + Number.EPSILON) : '--'
                const vatIndex = parseInt(this.vatPercentageTargets[i].value);
                if (!this.prices[vatIndex]) {
                    this.prices[vatIndex] = priceItem ?? 0;
                } else {
                    this.prices[vatIndex] = priceItem ? this.prices[vatIndex] + priceItem : this.prices[vatIndex];
                }
            } else {
                this.priceItemTargets[i].innerHTML = priceItem ? this.round(priceItem) : '--'
                this.priceTotal += priceItem ? priceItem : 0;
            }
        }

        let priceTotalWithoutVat = this.prices.reduce((a, b) => a + b, 0)

        if (this.isVatModeEnabled()) {
            this.priceTotalTarget.innerHTML = this.round(priceTotalWithoutVat)
            this.priceVatTarget.innerHTML = this.round(this.priceTotal - priceTotalWithoutVat)
        }

        this.priceTotalVatTarget.innerHTML = this.round(this.priceTotal)

    }

    async customerChange() {
        const customerId = this.customerTarget.value
        if (!customerId) {
            return
        }
        const vatData = await this.getVatProcessingData(customerId);

        this.vatsValue = vatData.vatRates;
        this.vatModeValue = vatData.vatMode;
        this.updateVatOptions();
        this.toggleDocumentWithWat();
        this.calc()
    }

    toggleDocumentWithWat() {
        const showVatElements = this.isVatModeEnabled();

        this.toggleClassList(this.documentWithVatTargets, 'd-none', showVatElements);

        this.itemNameTargets.forEach(element => {
            element.classList.toggle('col-md-5', showVatElements);
            element.classList.toggle('col-md-6', !showVatElements);
        });

        this.updateVatOptions();
    }

    toggleClassList(elements, className, shouldRemove) {
        elements.forEach(el => {
            el.classList[shouldRemove ? 'remove' : 'add'](className);
        });
    }

    async getVatProcessingData(customerValue) {
        const response = await fetch(`/api/1/document-vat-mode/${customerValue}`)
        if (!response.ok) {
            this.showAlert(`Společnost s tímto ID nebyla nalezena:${customerValue}`, 'warning');
            throw new Error(`HTTP error! statu: ${response.status}`)
        }

        return await response.json();
    }

    updateVatOptions() {
        const vatEntries = Object.entries(this.vatsValue);

        this.vatPercentageTargets.forEach(select => {
            select.innerHTML = '';
            vatEntries.forEach(([value, label]) => {
                select.appendChild(this.createOption(value, label));
            });
        });
    }

    createOption(value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        return option;
    }

    isVatModeEnabled() {
        return ['OSS', 'DOMESTIC'].includes(this.vatModeValue);
    }

    round(value) {
        return Math.round((value + Number.EPSILON) * 100) / 100;
    }
}