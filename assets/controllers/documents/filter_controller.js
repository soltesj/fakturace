import {Controller} from '@hotwired/stimulus';
import {useDebounce} from "stimulus-use";

export default class extends Controller {
    static targets = ['documentList', 'filterForm']
    static debounces = ['onChange', 'submitForm'];

    async connect() {
        useDebounce(this, {wait: 600});
        window.addEventListener('popstate', () => {
            this.populateFormFromUrl();
        });
        window.addEventListener('popstate', async () => {
            await this.onChange({fromHistory: true});
        });
        this.filterFormTarget.addEventListener("reset", async () => {
            await this.onChange();
        });


    }

    async submitForm(event) {
        event.preventDefault();
        await this.onChange();
    }

    async onChange({fromHistory = false} = {}) {
        if (!this.hasFilterFormTarget) {
            console.error('filterFormTarget is missing.');
            return;
        }

        const form = this.filterFormTarget;

        let actionUrl = form.action?.split('#')[0].split('?')[0];
        if (!actionUrl) {
            console.error('Missing or invalid form action URL.');
            return;
        }

        let finalUrl;
        if (fromHistory) {
            finalUrl = window.location.href;
        } else {
            const formData = new FormData(form);
            const queryString = new URLSearchParams(formData).toString();
            finalUrl = `${actionUrl}?${queryString}`;
            window.history.pushState({}, '', finalUrl);
        }
        this.documentListTarget.innerHTML = '<p>Načítám…</p>';
        try {
            const response = await fetch(finalUrl, {
                method: form.method,
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch: ${response.statusText}`);
            }
            this.documentListTarget.innerHTML = await response.text();
        } catch (e) {
            console.error('Error in onChange:', e.message);
            this.documentListTarget.innerHTML = `<p>${e.message || 'Error occurred. Please try again later.'}</p>`;
        }
    }

    populateFormFromUrl() {
        if (!this.hasFilterFormTarget) return;

        const form = this.filterFormTarget;
        const urlParams = new URLSearchParams(window.location.search);

        for (const [key, value] of urlParams.entries()) {
            const field = form.elements.namedItem(key);
            if (field) {
                field.value = value;
            }
        }
    }
}