import '@fontsource-variable/plus-jakarta-sans';
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

for (const form of document.querySelectorAll('form[data-confirm]')) {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const result = await Swal.fire({
            title: form.dataset.confirm,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0f766e',
        });

        if (result.isConfirmed) form.submit();
    });
}

const orderForm = document.querySelector('[data-order-form]');

if (orderForm) {
    const quantity = orderForm.elements.namedItem('quantity');
    const output = orderForm.querySelector('output');
    const price = Number(orderForm.dataset.unitPrice);
    const rupiah = new Intl.NumberFormat('id-ID');

    function updateEstimate() {
        output.value = quantity.validity.valid ? `Rp${rupiah.format(price * quantity.valueAsNumber)}` : '—';
    }

    quantity.addEventListener('input', updateEstimate);
    updateEstimate();
}
