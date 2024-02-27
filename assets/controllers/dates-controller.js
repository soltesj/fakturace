import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['dateIssue', 'dateTaxable', 'dateDue']
    static values = {
        maturityDays: Number,
    }

    changeDate(event) {
        let date = new Date(Date.parse(this.dateIssueTarget.value))
        date.setDate(date.getDate() + this.maturityDaysValue);
        this.dateTaxableTarget.value = date.toISOString().slice(0, 10)
        this.dateDueTarget.value = date.toISOString().slice(0, 10)
    }
}