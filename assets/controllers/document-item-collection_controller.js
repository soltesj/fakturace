import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['collectionContainer', 'vatAmount', 'isPriceWithVat', 'price']

    static values = {
        index: Number,
        prototype: String,
        isVatPayer: Boolean,
        vats: String,
    }

    connect() {
        this.toggleVat();
        if (this.indexValue === 0) {
            this.addCollectionElement();
            this.removeCollectionElement();
        }
    }

    toggleVat() {
        if (this.isVatPayerValue) {
            this.vatAmountTargets.forEach((element) => {
                element.classList.remove('d-none')
            })
            this.isPriceWithVatTargets.forEach((element) => {
                element.classList.remove('d-none')
            })
        } else {
            this.vatAmountTargets.forEach((element) => {
                element.classList.add('d-none')
            })
            this.isPriceWithVatTargets.forEach((element) => {
                element.classList.add('d-none')
            })
        }
    }

    addCollectionElement() {
        const item = document.createElement('div');
        item.classList.add('row');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        this.collectionContainerTarget.appendChild(item);
        this.indexValue++;
        this.toggleVat();
    }

    removeCollectionElement(e) {
        e.currentTarget.parentNode.parentNode.parentNode.remove()
    }
}