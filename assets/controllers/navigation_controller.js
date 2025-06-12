import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        "settingsMenu", "settingsSubmenu",
        "mobileMenuButton", "mobileMenu",
        "userMenu", "userSubmenu"
    ];
    timeout = null

    showSettingsMenu() {
        clearTimeout(this.timeout)
        this.settingsSubmenuTarget.classList.remove("hidden")
    }

    hideSettingsMenu() {
        this.timeout = setTimeout(() => {
            this.settingsSubmenuTarget.classList.add("hidden")
        }, 200)
    }

    toggleMobileMenu() {
        this.mobileMenuTarget.classList.toggle('hidden');
    }

    showMobileMenu() {
        this.mobileMenuTarget.classList.remove('hidden');
    }

    hideMobileMenu() {
        this.mobileMenuTarget.classList.add('hidden');
    }


    showUserMenu() {
        this.userSubmenuTarget.classList.remove('hidden');
    }

    hideUserMenu() {
        this.userSubmenuTarget.classList.add('hidden');
    }

    toggleUserMenu() {
        this.userSubmenuTarget.classList.toggle('hidden');
    }
}
