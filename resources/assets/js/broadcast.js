import Pusher from 'pusher-js'
import Echo from 'laravel-echo'

window.Pusher = Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '5b8cfc35f47f98895b06',
    encrypted: true
});