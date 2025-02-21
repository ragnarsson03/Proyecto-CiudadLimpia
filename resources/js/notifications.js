import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

const notificationCount = document.querySelector('.notification-count');
const notificationsList = document.querySelector('#notificationsList');

window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // Incrementar contador
        notificationCount.textContent = parseInt(notificationCount.textContent) + 1;
        
        // Agregar notificación a la lista
        const notificationItem = document.createElement('a');
        notificationItem.className = 'dropdown-item';
        notificationItem.href = notification.url || '#';
        notificationItem.textContent = notification.message;
        notificationsList.prepend(notificationItem);
    }); 