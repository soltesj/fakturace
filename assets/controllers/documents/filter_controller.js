import {Controller} from '@hotwired/stimulus';
import {useDebounce} from "stimulus-use";

export default class extends Controller {
    static targets = ['documentList']
    static debounces = ['onChange'];

   async connect() {
        await this.onChange();
        useDebounce(this);
    }

    async submitForm(event) {
        event.preventDefault();
        await this.onChange();
    }

    async onChange() {
        let form = this.element.getElementsByTagName('form')[0]
        let formData = new FormData(form);
        formData.append('isAjax', true)

        try {
            const response = await fetch(`${form.action}?${new URLSearchParams(formData).toString()}`, {
                method: form.method,
            });
            this.documentListTarget.innerHTML = await response.text();
        } catch (e) {
            console.error(e);
        }
    }
}