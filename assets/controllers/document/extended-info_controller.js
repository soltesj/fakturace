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
        console.log(event.currentTarget)
        event.currentTarget.innerHTML = this.showInformation ? '<svg class="size-4 mt-1"><use href="#plus-circle"/></svg><span>MORE_OPTIONS</span>' : '<svg class="size-4 mt-1"><use href="#minus-circle"/></svg><span>invoice.less_options</span>';
        this.showInformation = !this.showInformation;
        this.extendedInfoTargets.forEach((element) => {
            element.classList.toggle('hidden');
        })
    }

}