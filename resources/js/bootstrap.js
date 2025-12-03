import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Ensure Axios includes CSRF token from meta for 419 prevention
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Initialize Laravel Echo only when env variables are present to silence Livewire warnings
const createChannelStub = () => {
	const stub = {
		listen: () => stub,
		notification: () => stub,
		listenForWhisper: () => stub,
		whisper: () => stub,
		stopListening: () => stub,
		unsubscribe: () => undefined,
	};
	return stub;
};

const createEchoStub = () => {
	const channelStub = createChannelStub();
	const channelFactory = () => channelStub;
	return new Proxy({
		socketId: () => null,
		leave: () => undefined,
		leaveChannel: () => undefined,
		disconnect: () => undefined,
	}, {
		get(target, prop) {
			if (prop in target) {
				return target[prop];
			}
			// Any other method (channel/private/join, etc.) should return a channel stub
			return channelFactory;
		},
	});
};

const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
if (pusherKey) {
	window.Pusher = Pusher;

	window.Echo = new Echo({
		broadcaster: 'pusher',
		key: pusherKey,
		cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
		wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1'}.pusher.com`,
		wsPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 80),
		wssPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 443),
		forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
		enabledTransports: ['ws', 'wss'],
		csrfToken: token?.content ?? undefined,
	});
} else {
	console.warn('Laravel Echo is not configured. Falling back to a stub; set VITE_PUSHER_* env vars to enable real-time features.');
	window.Echo = createEchoStub();
}
