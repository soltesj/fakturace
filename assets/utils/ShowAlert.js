import AlertStyles from './AlertStyles'

/**
 * Vykreslí alert do daného cílového elementu.
 *
 * @param {HTMLElement} targetElement - Element, do kterého se má alert vložit.
 * @param {string} message - Zpráva k zobrazení.
 * @param {string} [type='error'] - Typ alertu: 'error', 'warning', 'info', 'success'.
 * @param {number} [timeout=null] - Po kolika ms má alert zmizet (null = nezmizí).
 */
export function showAlert(targetElement, message, type = 'error', timeout = null) {
    const alertClass = AlertStyles.get(type)

    const wrapper = document.createElement('div')
    wrapper.className = `
        mb-4 rounded border-l-4 p-4 ${alertClass}
        opacity-0 transition-opacity duration-600
    `.trim()
    wrapper.setAttribute('role', 'alert')

    wrapper.innerHTML = `
        ${message}
        <button type="button" class="btn-close float-end" aria-label="Zavřít">x</button>
    `

    // Zavírací tlačítko
    wrapper.querySelector('.btn-close').addEventListener('click', () => {
        fadeOutAndRemove(wrapper)
    })

    // Přidání do DOM a fade-in přes Tailwind (přidání opacity-100)
    targetElement.appendChild(wrapper)
    requestAnimationFrame(() => {
        wrapper.classList.remove('opacity-0')
        wrapper.classList.add('opacity-100')
    })

    // Automatické zavření po timeoutu
    if (timeout !== null) {
        setTimeout(() => {
            fadeOutAndRemove(wrapper)
        }, timeout)
    }
}

function fadeOutAndRemove(element) {
    element.classList.remove('opacity-100')
    element.classList.add('opacity-0')
    setTimeout(() => element.remove(), 600) // match duration-300
}