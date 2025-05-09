import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['collectionContainer']


    static values = {
        index: Number,
        prototype: String,
        isVatPayer: Boolean,
        vats: String,
    }

    connect() {
        if (this.indexValue === 0) {
            this.addCollectionElement();
            this.removeCollectionElement();
        }
    }

    addCollectionElement() {
        const item = document.createElement('div');
        item.classList.add('row');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        this.collectionContainerTarget.appendChild(item);
        this.indexValue++;

        this.dispatch('add-row')
    }

    removeCollectionElement(e) {
        const container = e.currentTarget.closest('.row')
        if (container) {
            container.remove();
        } else {
            console.warn('No valid .row container found for removal.');
        }
    }
}