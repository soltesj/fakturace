import {Controller} from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ['customerId', 'customerName', 'submitButton']

    connect() {
        console.log('validator')
        this.customerIdValidation()

        this.observer = new MutationObserver(() => this.customerIdValidation())
        this.observer.observe(this.customerIdTarget, {
            attributes: true,
            attributeFilter: ["value"],
        })

    }

    submit(event) {
        event.preventDefault()
        console.log(event)
        const isValid = this.customerIdValidation()
        if (isValid) {
            event.target.submit()
        } else {
            this.submitButtonTarget.classList.remove('bg-green-700')
            this.submitButtonTarget.classList.add('bg-green-950')
        }
    }

    disconnect() {
        this.observer.disconnect()
    }

    customerIdValidation() {
        const customerId = parseInt(this.customerIdTarget.value)
        const customerName = this.customerNameTarget.innerHTML.trim().replace('&nbsp;', '')
        console.log(customerName)
        if (customerId > 0 && customerName.length > 1) {
            this.submitButtonTarget.removeAttribute("disabled", "");
            this.customerNameTarget.classList.remove('border-red-700')
            // this.submitButtonTarget.classList.remove('bg-green-950')
            // this.submitButtonTarget.classList.add('hover:bg-green-500')
            // this.submitButtonTarget.classList.add('bg-green-700')
            return true
        } else {
            this.submitButtonTarget.setAttribute("disabled", "");
            this.customerNameTarget.classList.add('border-red-700')
            // this.submitButtonTarget.classList.add('bg-green-950')
            // this.submitButtonTarget.classList.remove('hover:bg-green-500')
            // this.submitButtonTarget.classList.remove('bg-green-700')
            return false
        }
    }
}