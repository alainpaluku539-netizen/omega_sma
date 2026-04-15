import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080, // Port par défaut de Reverb
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false, // Force le passage en ws:// au lieu de wss://
    enabledTransports: ['ws', 'wss'],
});

