import {Controller} from '@hotwired/stimulus';
import {useDebounce, useClickOutside} from "stimulus-use";


export default class extends Controller {
    static debounces = ['onChange',];
    static targets = ['customer', 'customerId', 'searchInput', 'list', 'name', 'caretDown', 'caretUp', 'searchBlock']
    static values = {
        apiUrl: String,
    }
    isOpen = false

    connect() {
        useDebounce(this, {wait: 600});
        useClickOutside(this)
    }

    async onChange() {
        if (this.searchInputTarget.value.length === 0) {
            return
        }
        let url = `${decodeURI(this.apiUrlValue)}/${this.searchInputTarget.value}`
        console.log(url)
        const response = await fetch(url)
        if (!response.ok) {
            throw new Error(`HTTP error! statu: ${response.status}`)
        }
        this.listTarget.innerHTML = await response.text();
    }

    toggle() {
        this.caretDownTarget.classList.toggle('hidden')
        this.caretUpTarget.classList.toggle('hidden')
        this.searchBlockTarget.classList.toggle('hidden');
        this.searchInputTarget.focus()
    }

    pickCustomer(event) {
        this.customerIdTarget.value = event.currentTarget.dataset.customerId
        this.nameTarget.innerHTML = event.currentTarget.dataset.customerName

        this.dispatch('customer:change')
        this.toggle()
    }

    clickOutside() {
        this.close()
    }

    close() {
        this.caretDownTarget.classList.remove('hidden')
        this.caretUpTarget.classList.add('hidden')
        this.searchBlockTarget.classList.add('hidden');
    }
}