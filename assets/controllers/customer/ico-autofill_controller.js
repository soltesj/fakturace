import {Controller} from '@hotwired/stimulus'
import {showAlert} from '../../utils/ShowAlert'

export default class extends Controller {
    static targets = [
        'alertBox', 'companyNumberInput', 'nameInput', 'streetInput', 'houseNumberInput',
        'townInput', 'zipcodeInput', 'vatNumberInput', 'isVatPayerInput', 'countryInput',
        'lookupButton', 'buttonSpinner', 'buttonLabel'
    ]

    async lookup() {
        const country = this.countryInputTarget.value.trim()
        const companyNumber = this.companyNumberInputTarget.value.trim()

        if (!this.isValidCompanyNumber(companyNumber)) {
            this.showWarning('IČO musí mít 8 číslic')
            return
        }

        this.toggleButtonLoading(true)

        try {
            const data = await this.fetchCompanyData(country, companyNumber)
            this.fillForm(data)
            this.showInfo('Údaje byly úspěšně načteny.')
        } catch (error) {
            this.showWarning('Společnost s tímto IČO nebyla nalezena.')
            console.error(error)
        } finally {
            this.toggleButtonLoading(false)
        }
    }

    isValidCompanyNumber(ico) {
        return /^\d{8}$/.test(ico)
    }

    async fetchCompanyData(country, companyNumber) {
        const response = await fetch(`/api/company-registry/${country}/${companyNumber}`)
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`)
        return await response.json()
    }

    fillForm(data) {
        this.nameInputTarget.value = data.name || ''
        this.streetInputTarget.value = data.street || ''
        this.houseNumberInputTarget.value = data.houseNumber || ''
        this.townInputTarget.value = data.town || ''
        this.zipcodeInputTarget.value = data.zipcode || ''
        this.vatNumberInputTarget.value = data.vatNumber || ''
        this.isVatPayerInputTarget.checked = !!data.isVatPayer
    }

    toggleButtonLoading(isLoading) {
        this.buttonSpinnerTarget.classList.toggle('hidden', !isLoading)
        this.buttonLabelTarget.classList.toggle('hidden', isLoading)
        this.lookupButtonTarget.disabled = isLoading
    }

    showWarning(message, timeout = 3000) {
        showAlert(this.alertBoxTarget, message, 'warning', timeout)
    }

    showInfo(message, timeout = 3000) {
        showAlert(this.alertBoxTarget, message, 'info', timeout)
    }
}