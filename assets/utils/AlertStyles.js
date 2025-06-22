export default class AlertStyles {
    static get(type) {
        const messageColors = {
            error: 'border-red-500 text-white/80 hover:text-white/90',
            warning: 'border-yellow-500 text-white/80 hover:text-white/90',
            info: 'border-blue-500 bg-blue-100 text-white/80 hover:text-white/90',
            success: 'border-green-500 text-white/80 hover:text-white/90',
        };

        return messageColors[type] || messageColors.error;
    }
}