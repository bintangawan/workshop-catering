import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const notification = document.querySelector('[data-flash-message]');

if (notification) {
    Swal.fire({
        icon: notification.dataset.flashIcon,
        text: notification.dataset.flashMessage,
        confirmButtonText: 'OK',
    });
}
