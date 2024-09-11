<script>
import io from 'socket.io-client';

const socket = io('http://localhost:3000');

export default {
    data() {
        return {
            notifications: []
        };
    },
    mounted() {
        socket.on('receiveNotification', (data) => {
            this.notifications.push(data);
        });
    },
    methods: {
        sendNotification(message) {
            socket.emit('sendNotification', { message });
        }
    }
}
</script>
