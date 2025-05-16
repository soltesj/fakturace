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
                'background-secondary': 'rgba(var(--color-background-secondary))',
                border: 'rgba(var(--color-border))',
                'text-primary': 'rgba(var(--color-text-primary))',
                'text-secondary': 'rgba(var(--color-text-secondary))',
                'table-header': 'rgba(var(--color-table-header))',
                'input-bg': 'rgba(var(--color-form-input-bg))',
                'input-border': 'rgba(var(--color-form-input-border))',
                'input-border-focus': 'rgba(var(--color-form-input-border-focus))',

            }
        },
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'base', // only generate global styles
            // strategy: 'class', // only generate classes
        }),
    ],
}

