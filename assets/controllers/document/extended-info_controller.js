import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    showInformation = false;
    static targets = ['extendedInfo', 'div']

    connect() {
        this.extendedInfoTargets.forEach((element) => {
            element.classList.add('d-none');
        })
    }

    toggleInfo(event) {
        event.currentTarget.innerHTML = this.showInformation ? '<svg width="16" height="16" class="bi me-1"><use href="#plus-circle"/></svg>Více informací' : '<svg width="16" height="16" class="bi me-1"><use href="#minus-circle"/></svg> Méně informací';
        this.showInformation = !this.showInformation;
        this.extendedInfoTargets.forEach((element) => {
            element.classList.toggle('d-none');
        })
    }

}