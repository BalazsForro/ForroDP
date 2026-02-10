import './bootstrap';
import * as bootstrap from 'bootstrap';

document.addEventListener('livewire:init', () => {
    Livewire.on('bs-modal-open', ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        const modal = bootstrap.Modal.getOrCreateInstance(el);
        modal.show();
    });

    Livewire.on('bs-modal-close', ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        const modal = bootstrap.Modal.getOrCreateInstance(el);
        modal.hide();
    });

    Livewire.on('bs-enable-tooltips', () => {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    })

    //Toast
    initToast();
});

function initToast() {
    let toastCreatedAt = null;
    let toastInterval = null;

    function formatTimeAgo(seconds) {
        if (seconds < 60) return `${seconds}s ago`;
        const mins = Math.floor(seconds / 60);
        return `${mins}m ago`;
    }

    Livewire.on('bs-toast-show', ({ message }) => {
        const el = document.getElementById('mainToast');
        if (!el) return;

        const body = document.getElementById('toastBody');
        const timeEl = document.getElementById('toastTime');

        body.textContent = message;

        toastCreatedAt = Date.now();

        // azonnali idő
        timeEl.textContent = 'just now';

        // régi interval törlése
        if (toastInterval) {
            clearInterval(toastInterval);
        }

        // frissítjük az időt
        toastInterval = setInterval(() => {
            const diffSeconds = Math.floor((Date.now() - toastCreatedAt) / 1000);
            timeEl.textContent = formatTimeAgo(diffSeconds);
        }, 1000);

        const toast = bootstrap.Toast.getOrCreateInstance(el, {
            delay: 5000
        });

        toast.show();

        // auto cleanup
        el.addEventListener('hidden.bs.toast', () => {
            clearInterval(toastInterval);
            toastInterval = null;
        }, { once: true });
    });
}
