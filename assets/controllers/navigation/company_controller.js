import {Controller} from '@hotwired/stimulus';
import {useClickOutside} from "stimulus-use";

export default class extends Controller {
    static targets = [
        "submenu",
    ];

    connect() {
        useClickOutside(this)
    }

    hideMenu() {
        this.submenuTarget.classList.add('hidden');
    }

    toggleMenu() {
        this.submenuTarget.classList.toggle('hidden');
    }

    clickOutside() {
        this.hideMenu()
    }
}
