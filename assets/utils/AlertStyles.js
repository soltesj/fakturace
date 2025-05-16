export default class AlertStyles {
    static get(type) {
        const messageColors = {
            error: 'border-red-500 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100',
            warning: 'border-yellow-500 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100',
            info: 'border-blue-500 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
            success: 'border-green-500 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100',
        };

        return messageColors[type] || messageColors.error;
    }
}