import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['alertBox', 'companyNumberInput', 'nameInput', 'streetInput', 'houseNumberInput', 'townInput', 'zipcodeInput',
        'vatNumberInput', 'isVatPayerInput', 'countryInput']


    async lookup() {

        const country = this.countryInputTarget.value.trim()
        const companyNumber = this.companyNumberInputTarget.value.trim()

        if (!/^\d{8}$/.test(companyNumber)) {
            this.showAlert('IČO musí mít 8 číslic', 'warning');
            return
        }

        try {
            const response = await fetch(`/api/company-registry/${country}/${companyNumber}`)
            if (!response.ok) {
                this.showAlert('Společnost s tímto IČO nebyla nalezena.', 'warning');
                throw new Error(`HTTP error! statu: ${response.status}`)
            }

            const data = await response.json()

            this.nameInputTarget.value = data.name || ''
            this.streetInputTarget.value = data.street || ''
            this.houseNumberInputTarget.value = data.houseNumber
            this.townInputTarget.value = data.town
            this.zipcodeInputTarget.value = data.zipcode || ''
            this.vatNumberInputTarget.value = data.vatNumber || ''
            this.isVatPayerInputTarget.checked = data.isVatPayer || false;
            this.showAlert('Údaje byly úspěšně načteny.', 'info');
        } catch (error) {
            this.showAlert('Chyba při načítání dat podle IČO', 'danger')
        }
    }

    showAlert(message, type = 'danger') {
        this.alertBoxTarget.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    }
}