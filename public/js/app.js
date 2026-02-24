/**
 * BiblioDigital — Client-side interactions
 */

// ── Toast Notification System ──
const Toast = {
    container: null,

    init() {
        this.container = document.getElementById('toast-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },

    show(message, type = 'info', duration = 4000) {
        if (!this.container) this.init();

        const icons = {
            success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            ${icons[type] || icons.info}
            <span>${message}</span>
            <button class="toast-close" onclick="Toast.dismiss(this.parentElement)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        `;

        this.container.appendChild(toast);

        setTimeout(() => this.dismiss(toast), duration);
    },

    dismiss(toast) {
        if (!toast || toast.classList.contains('removing')) return;
        toast.classList.add('removing');
        setTimeout(() => toast.remove(), 300);
    }
};

// ── Form Handler (AJAX) ──
function handleForm(formId, options = {}) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalHtml = submitBtn.innerHTML;

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="spinner"></div> Procesando...';

        try {
            const formData = new FormData(form);

            const response = await fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData,
            });
            const data = await response.json();

            if (data.success) {
                Toast.show(data.message, 'success');
                if (data.redirect) {
                    setTimeout(() => window.location.href = data.redirect, 800);
                }
                if (options.onSuccess) options.onSuccess(data);
            } else {
                Toast.show(data.message, 'error');
                if (options.onError) options.onError(data);
            }
        } catch (error) {
            Toast.show('Error de conexión. Intenta de nuevo.', 'error');
            console.error(error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    });
}

// ── Mobile Navigation ──
function initMobileNav() {
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.navbar-nav');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', () => {
        nav.classList.toggle('open');
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!toggle.contains(e.target) && !nav.contains(e.target)) {
            nav.classList.remove('open');
        }
    });
}

// ── Confirm Delete ──
function confirmDelete(url, itemName = 'este elemento') {
    if (confirm(`¿Estás seguro de que deseas eliminar ${itemName}? Esta acción no se puede deshacer.`)) {
        fetch(url, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Toast.show(data.message, 'success');
                    setTimeout(() => location.reload(), 800);
                } else {
                    Toast.show(data.message, 'error');
                }
            })
            .catch(() => Toast.show('Error al eliminar.', 'error'));
    }
}

// ── Password Visibility Toggle ──
function togglePassword(btn) {
    const input = btn.parentElement.querySelector('input');
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    }
}

// ── Debounced Search ──
function initSearch(inputId, callback, delay = 400) {
    const input = document.getElementById(inputId);
    if (!input) return;

    let timer;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => callback(input.value), delay);
    });
}

// ── Initialize on DOM ready ──
document.addEventListener('DOMContentLoaded', () => {
    Toast.init();
    initMobileNav();

    // Show PHP flash messages as toasts
    const flashEl = document.getElementById('flash-data');
    if (flashEl) {
        const type = flashEl.dataset.type;
        const message = flashEl.dataset.message;
        if (message) {
            Toast.show(message, type);
        }
    }
});
