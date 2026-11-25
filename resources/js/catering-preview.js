import Swal from 'sweetalert2';

const rupiah = new Intl.NumberFormat('id-ID');
const currency = (value) => `Rp${rupiah.format(value)}`;
const dateFormatter = new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium' });
const labels = { pending: 'Menunggu', confirmed: 'Dikonfirmasi', completed: 'Selesai' };
const sampleOrderKey = 'catering-preview-orders';
const statusKey = 'catering-preview-statuses';

function readDemo(key, fallback) {
    try {
        return JSON.parse(sessionStorage.getItem(key)) ?? fallback;
    } catch {
        return fallback;
    }
}

function saveDemo(key, value) {
    try {
        sessionStorage.setItem(key, JSON.stringify(value));
    } catch {
        // Preview actions can still be tested in place when storage is unavailable.
    }
}

function showFeedback(title, text) {
    return Swal.fire({ title, text, icon: 'success', confirmButtonColor: '#0f766e' });
}

function renderStatus(badge, status) {
    const dot = document.createElement('span');
    dot.setAttribute('aria-hidden', 'true');
    badge.className = `status status-${status}`;
    badge.replaceChildren(dot, labels[status]);
}

const menuDialog = document.querySelector('[data-menu-dialog]');

if (menuDialog) {
    const menuForm = menuDialog.querySelector('form');
    const grid = document.querySelector('[data-menu-grid]');
    const template = document.querySelector('[data-new-menu]');
    let editingCard = null;

    function updateCard(card, menu) {
        card.querySelector('[data-menu-name]').textContent = menu.name;
        card.querySelector('[data-menu-description]').textContent = menu.description;
        card.querySelector('[data-menu-price]').textContent = currency(menu.price);
        card.querySelector('[data-menu-form-open]').dataset.menu = JSON.stringify(menu);
        const status = card.querySelector('[data-menu-status]');
        status.textContent = menu.is_active ? 'Aktif' : 'Nonaktif';
        status.className = `status ${menu.is_active ? 'status-completed' : 'status-neutral'}`;
        const toggle = card.querySelector('[data-menu-toggle]');
        if (toggle) toggle.textContent = menu.is_active ? 'Nonaktifkan' : 'Aktifkan';
    }

    document.addEventListener('click', async (event) => {
        const open = event.target.closest('[data-menu-form-open]');

        if (open) {
            const menu = open.dataset.menu ? JSON.parse(open.dataset.menu) : null;
            editingCard = open.closest('[data-menu-card]');
            menuForm.reset();
            menuDialog.querySelector('[data-dialog-title]').textContent = menu ? 'Edit menu' : 'Tambah menu';

            if (menu) {
                menuForm.elements.namedItem('name').value = menu.name;
                menuForm.elements.namedItem('price').value = menu.price;
                menuForm.elements.namedItem('description').value = menu.description ?? '';
                menuForm.elements.namedItem('is_active').checked = menu.is_active;
            }
            menuDialog.showModal();
        }

        if (event.target.closest('[data-dialog-close]')) menuDialog.close();

        const toggle = event.target.closest('[data-menu-toggle]');
        if (toggle) {
            const card = toggle.closest('[data-menu-card]');
            const menu = JSON.parse(card.querySelector('[data-menu-form-open]').dataset.menu);
            updateCard(card, { ...menu, is_active: !menu.is_active });
            await showFeedback('Status menu diperbarui', 'Perubahan ini hanya berlaku pada halaman simulasi.');
        }

        const remove = event.target.closest('[data-menu-delete]');
        if (remove) {
            const decision = await Swal.fire({
                title: 'Hapus menu contoh?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#b91c1c',
            });

            if (decision.isConfirmed) remove.closest('[data-menu-card]').remove();
        }
    });

    menuForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const data = new FormData(menuForm);
        const name = data.get('name').trim();
        if (!name) {
            const input = menuForm.elements.namedItem('name');
            input.value = '';
            input.reportValidity();
            return;
        }

        const card = editingCard ?? template.content.firstElementChild.cloneNode(true);
        updateCard(card, {
            name,
            price: Number(data.get('price')),
            description: data.get('description').trim(),
            is_active: data.has('is_active'),
        });
        if (!editingCard) grid.prepend(card);
        menuDialog.close();
        await showFeedback('Menu disimpan di tampilan', 'Muat ulang halaman untuk kembali ke data awal.');
    });
}

const orderForm = document.querySelector('[data-order-form]');

if (orderForm) {
    const quantity = orderForm.elements.namedItem('quantity');
    const price = Number(orderForm.dataset.unitPrice);
    const total = orderForm.querySelector('output');

    quantity.addEventListener('input', () => {
        total.value = quantity.validity.valid ? currency(price * quantity.valueAsNumber) : '—';
    });

    orderForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const portions = quantity.valueAsNumber;
        const timestamp = Date.now();
        const samples = readDemo(sampleOrderKey, []);
        samples.unshift({
            id: `demo-${timestamp}`,
            user_id: Number(orderForm.dataset.customerId),
            menu_id: Number(orderForm.dataset.menuId),
            menuName: orderForm.dataset.menuName,
            quantity: portions,
            unit_price: price,
            status: 'pending',
            created_at: timestamp,
        });
        saveDemo(sampleOrderKey, samples);
        await showFeedback('Pesanan simulasi dibuat', `${portions} porsi · ${currency(price * portions)}`);
        window.location.assign(orderForm.dataset.orderHistory);
    });
}

const list = document.querySelector('[data-demo-order-list]');

if (list) {
    const admin = list.dataset.demoOrderList === 'admin';
    const samples = readDemo(sampleOrderKey, []);
    if (samples.length) list.querySelector('[data-empty-orders]')?.closest('tr').remove();

    for (const order of [...samples].reverse()) {
        const row = list.insertRow(0);
        const date = dateFormatter.format(order.created_at);
        const details = admin
            ? ['Simulasi · '+date, order.menuName, list.dataset.demoCustomer, order.quantity]
            : ['Simulasi · '+date, order.menuName, order.quantity, currency(order.unit_price)];

        for (const value of [...details, currency(order.quantity * order.unit_price)]) {
            row.insertCell().textContent = value;
        }

        const badge = document.createElement('span');
        badge.dataset.orderStatus = order.id;
        renderStatus(badge, order.status);
        row.insertCell().append(badge);

        if (admin) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'button button-small button-outline';
            button.dataset.orderAction = order.id;
            button.dataset.currentStatus = order.status;
            row.insertCell().append(button);
        }
    }
}

const statuses = readDemo(statusKey, {});
for (const badge of document.querySelectorAll('[data-order-status]')) {
    const status = statuses[badge.dataset.orderStatus];
    if (Object.hasOwn(labels, status)) renderStatus(badge, status);
}

function updateOrderButton(button, status) {
    button.dataset.currentStatus = status;
    button.hidden = status === 'completed';
    button.textContent = status === 'pending' ? 'Konfirmasi' : 'Tandai selesai';
}

for (const button of document.querySelectorAll('[data-order-action]')) {
    const id = button.dataset.orderAction;
    updateOrderButton(button, statuses[id] ?? button.dataset.currentStatus);

    button.addEventListener('click', async () => {
        const next = button.dataset.currentStatus === 'pending' ? 'confirmed' : 'completed';
        const decision = await Swal.fire({
            title: `Ubah status menjadi ${labels[next]}?`,
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0f766e',
        });
        if (!decision.isConfirmed) return;

        statuses[id] = next;
        saveDemo(statusKey, statuses);
        renderStatus(button.closest('tr').querySelector('[data-order-status]'), next);
        updateOrderButton(button, next);
        await showFeedback('Status pesanan diperbarui', 'Perubahan tersimpan untuk sesi peramban ini.');
    });
}
