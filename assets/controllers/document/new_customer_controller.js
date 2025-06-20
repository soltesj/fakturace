import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
    static targets = [
        'modal', 'content', 'body', 'form', "customerId", 'customerName'
    ]
    static values = {
        apiUrl: String,
    }

    async fetchCompanyData(country, companyNumber) {
        return fetch(this.buildUrl(country, companyNumber));
    }


    async submit(event) {
        event.preventDefault()
        const form = this.formTarget
        const formData = new FormData(form);
        const response = await fetch(this.apiUrlValue, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })

        const contentType = response.headers.get('Content-Type')

        if (contentType.includes('application/json')) {
            const data = await response.json()
            this.handleSuccess(data)
        } else {
            const html = await response.text()
            this.handleFormErrors(html)
        }
    }

    handleSuccess(customer) {
        let customerName = `<span class="font-semibold">${customer.name}</span>`

        if (customer.companyNumber) {
            customerName += ` <span class="text-sm">(form.company.businessId: ${customer.companyNumber})</span>`
        }

        this.customerIdTarget.value = customer.id
        this.customerNameTarget.innerHTML = customerName

        this.close()
    }

    handleFormErrors(html) {
        this.element.innerHTML = html
    }

    async open() {
        const modal = this.modalTarget;
        modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
        modal.classList.add('opacity-100', 'scale-100');
        document.body.classList.add('overflow-hidden');
        await this.loadForm();
    }

    async loadForm() {
        const response = await fetch(this.apiUrlValue, {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        });
        this.bodyTarget.innerHTML = await response.text();
    }

    onOverlayClick(event) {
        if (!this.contentTarget.contains(event.target)) {
            this.close()
        }
    }

    close() {
        this.bodyTarget.innerHTML = ''
        const modal = this.modalTarget;
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');
        document.body.classList.remove('overflow-hidden');
        setTimeout(() => {
            modal.classList.add('pointer-events-none');
        }, 500);
    }
}