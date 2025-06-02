import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ['modal', 'documentId', 'amount', 'form'];
    static values = {
        id: Number,
        amount: Number,
        url: String
    };

    connect() {
        this.formTarget.addEventListener("submit", this.submit.bind(this))
    }

    async submit(event) {
        event.preventDefault()

        const formData = new FormData(this.formTarget)
        const response = await fetch(this.formTarget.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })

        if (!response.ok) {
            this.formTarget.innerHTML = await response.text()
            return
        }

        this.dispatch("document:paid");
        this.close()
    }

    open(event) {
        const modal = this.modalTarget;
        const button = event.currentTarget;
        this.documentIdTarget.value = button.dataset.paymentDocumentId;
        this.amountTarget.value = button.dataset.paymentAmount;
        modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
        modal.classList.add('opacity-100', 'scale-100');
    }

    close() {
        const modal = this.modalTarget;
        modal.classList.remove('opacity-100', 'scale-100');
        modal.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            modal.classList.add('pointer-events-none');
        }, 500);
    }
}