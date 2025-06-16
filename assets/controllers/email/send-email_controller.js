import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ['form', 'modal', 'documentId', 'to', 'subject', 'content'];
    static values = {
        id: Number,
        subjectTemplate: String,
        contentTemplate: String,
        userName: String,
        companyName: String,
    };

    connect() {
        this.formTarget.addEventListener("submit", this.submit.bind(this))
    }

    async submit(event) {
        event.preventDefault()
        console.log('.')

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

        // this.dispatch("document:paid");
        this.close()
    }

    open(event) {
        const modal = this.modalTarget;
        const button = event.currentTarget;
        this.documentIdTarget.value = button.dataset.documentId;
        const documentNumber = button.dataset.documentNumber;
        let subjectTemplate = this.subjectTemplateValue;
        let contentTemplate = this.contentTemplateValue;

        this.toTarget.value = button.dataset.email;

        this.contentTarget.value = this.renderTemplate(contentTemplate, {
            number: documentNumber,
            company: this.companyNameValue,
            username: this.userNameValue,
        });
        this.subjectTarget.value = this.renderTemplate(subjectTemplate, {
            number: documentNumber,
        });
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

    renderTemplate(template, variables) {
        return template.replace(/\{(\w+)}/g, (_, key) => variables[key] || '');
    }
}