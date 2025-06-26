import {Controller} from '@hotwired/stimulus';
import {useClickOutside} from "stimulus-use";

export default class extends Controller {
    static targets = [
        "mobileMenuButton", "openMenuIcon", "closeMenuIcon", "mobileMenu",
    ];

    connect() {
        useClickOutside(this, {
            element: this.mobileMenuTarget
        })
    }

    toggleMobileMenu() {
        this.mobileMenuTarget.classList.toggle('hidden');
        this.openMenuIconTarget.classList.toggle('hidden');
        this.closeMenuIconTarget.classList.toggle('hidden');
    }

    hideMobileMenu() {
        this.mobileMenuTarget.classList.add('hidden');
    }

    clickOutside(event) {
        const path = event.composedPath();

        if (
            path.includes(this.mobileMenuTarget) ||
            path.includes(this.mobileMenuButtonTarget)
        ) {
            return;
        }

        this.hideMobileMenu()
    }
}
