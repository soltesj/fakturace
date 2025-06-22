/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './templates/**/*.html.twig',
        './assets/**/*.{js,jsx,ts,tsx}',
    ],
    theme: {
        extend: {
            colors: {
                primary: 'rgba(var(--color-primary))',
                secondary: 'rgba(var(--color-secondary))',
                background: 'rgba(var(--color-background))',
                'card-bg': 'rgba(var(--color-card-bg))',
                border: 'rgba(var(--color-border))',
                'text-primary': 'rgba(var(--color-text-primary))',
                'text-secondary': 'rgba(var(--color-text-secondary))',
                'table-header': 'rgba(var(--color-card-header-bg))',
                'input-bg': 'rgba(var(--color-form-input-bg))',
                'input-border': 'rgba(var(--color-form-input-border))',
                'input-border-focus': 'rgba(var(--color-form-input-border-focus))',

                'success': 'rgba(var(--color-success))',
                'success-text': 'rgba(var(--color-success-text))',
                'info': 'rgba(var(--color-info))',
                'info-text': 'rgba(var(--color-info-text))',
                'warning': 'rgba(var(--color-warning))',
                'warning-text': 'rgba(var(--color-warning-text))',
                'error': 'rgba(var(--color-error))',
                'error-text': 'rgba(var(--color-error-text))',

                'nav-background': 'rgba(var(--color-nav-background))',
                'nav-background-secondary': 'rgba(var(--color-nav-background-secondary))',

            },
            order: {
                13: '13',
                14: '14',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'base', // only generate global styles
            // strategy: 'class', // only generate classes
        }),
    ],
}

