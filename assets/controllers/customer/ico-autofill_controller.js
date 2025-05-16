import {Controller} from '@hotwired/stimulus'
import {showAlert} from '../../utils/ShowAlert'

const COMPANY_NUMBER_REGEX = /^\d{8}$/;

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

        this.setButtonState(true)
        this.toggleButtonLoading(true)
        try {
            const response = await this.fetchCompanyData(country, companyNumber);


            if (response.status === 429) {
                const retryAfter = parseInt(response.headers.get('X-RateLimit-Retry-After') || '1') + 1
                this.showWarning(`Překročen limit. Zkus to znovu za ${retryAfter} s.`, retryAfter * 1000)

                setTimeout(() => this.setButtonState(false), retryAfter * 1000)
                return
            }

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`)
            }

            const data = await response.json()
            this.fillForm(data)
            this.showInfo('Údaje byly úspěšně načteny.')
        } catch (error) {
            console.error(error)
            this.showWarning('Společnost s tímto IČO nebyla nalezena.')
        } finally {
            this.toggleButtonLoading(false)
        }
        this.setButtonState(false)
    }

    async fetchCompanyData(country, companyNumber) {
        return fetch(this.buildUrl(country, companyNumber));
    }

    isValidCompanyNumber(companyNumber) {
        return COMPANY_NUMBER_REGEX.test(companyNumber);
    }

    buildUrl(country, companyNumber) {
        return `/api/company-registry/${country}/${companyNumber}`
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
    }

    setButtonState(disabled) {
        this.lookupButtonTarget.disabled = disabled
    }

    showWarning(message, timeout = 3000) {
        showAlert(this.alertBoxTarget, message, 'warning', timeout)
    }

    showInfo(message, timeout = 3000) {
        showAlert(this.alertBoxTarget, message, 'info', timeout)
    }
}