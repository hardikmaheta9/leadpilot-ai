import './bootstrap';
import './search';
import './dashboard';

document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================
       Floating Quick Add
    ========================================== */

    const quickBtn = document.getElementById('quickAddToggle');
    const quickMenu = document.getElementById('quickAddMenu');

    if (quickBtn && quickMenu) {
        quickBtn.addEventListener('click', function (event) {
            event.stopPropagation();
            quickMenu.classList.toggle('show');
        });
    }

    /* ==========================================
       Notification Dropdown
    ========================================== */

    const notificationBtn = document.getElementById('notificationToggle');
    const notificationPanel = document.getElementById('notificationPanel');

    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener('click', function (event) {
            event.stopPropagation();
            notificationPanel.classList.toggle('show');
        });
    }

    document.addEventListener('click', function (event) {
        if (
            quickBtn &&
            quickMenu &&
            !quickBtn.contains(event.target) &&
            !quickMenu.contains(event.target)
        ) {
            quickMenu.classList.remove('show');
        }

        if (
            notificationBtn &&
            notificationPanel &&
            !notificationBtn.contains(event.target) &&
            !notificationPanel.contains(event.target)
        ) {
            notificationPanel.classList.remove('show');
        }
    });

    /* ==========================================
       Toast Notifications
    ========================================== */

    const toasts = document.querySelectorAll('[data-lp-toast]');

    const closeToast = function (toast) {
        if (!toast || toast.classList.contains('is-hiding')) {
            return;
        }

        toast.classList.add('is-hiding');

        window.setTimeout(function () {
            toast.remove();
        }, 300);
    };

    toasts.forEach(function (toast) {
        const closeButton = toast.querySelector('[data-lp-toast-close]');

        if (closeButton) {
            closeButton.addEventListener('click', function () {
                closeToast(toast);
            });
        }

        window.setTimeout(function () {
            closeToast(toast);
        }, 4500);
    });

    /* ==========================================
       Global Delete Confirmation
    ========================================== */

    let pendingDeleteForm = null;
    let deleteConfirmed = false;

    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form[data-confirm-delete]');

        if (!form || deleteConfirmed) {
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();

        pendingDeleteForm = form;

        const messageElement = document.getElementById('deleteConfirmMessage');

        if (messageElement) {
            messageElement.textContent =
                form.dataset.confirmMessage ||
                'This action cannot be undone.';
        }

        const modalElement = document.getElementById('deleteConfirmModal');

        if (modalElement && window.bootstrap) {
            window.bootstrap.Modal
                .getOrCreateInstance(modalElement)
                .show();
        }
    }, true);

    const confirmDeleteButton = document.getElementById('confirmDeleteButton');

    if (confirmDeleteButton) {
        confirmDeleteButton.addEventListener('click', function () {
            if (!pendingDeleteForm) {
                return;
            }

            const formToSubmit = pendingDeleteForm;
            pendingDeleteForm = null;
            deleteConfirmed = true;

            const modalElement = document.getElementById('deleteConfirmModal');

            if (modalElement && window.bootstrap) {
                window.bootstrap.Modal
                    .getOrCreateInstance(modalElement)
                    .hide();
            }

            formToSubmit.removeAttribute('data-confirm-delete');

            window.setTimeout(function () {
                formToSubmit.requestSubmit();
            }, 150);
        });
    }

    /* ==========================================
       Loading Buttons
    ========================================== */

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (
                form.hasAttribute('data-confirm-delete') &&
                !deleteConfirmed
            ) {
                return;
            }

            const submitButtons = form.querySelectorAll(
                'button[type="submit"], input[type="submit"]'
            );

            submitButtons.forEach(function (button) {
                if (button.dataset.loadingApplied) {
                    return;
                }

                button.dataset.loadingApplied = '1';
                button.disabled = true;

                const original = button.innerHTML || button.value || '';
                button.dataset.original = original;

                const originalLower = original.toLowerCase();
                let text = 'Processing...';

                if (originalLower.includes('save')) {
                    text = 'Saving...';
                } else if (originalLower.includes('update')) {
                    text = 'Updating...';
                } else if (originalLower.includes('upload')) {
                    text = 'Uploading...';
                } else if (originalLower.includes('delete')) {
                    text = 'Deleting...';
                } else if (originalLower.includes('complete')) {
                    text = 'Completing...';
                } else if (originalLower.includes('add')) {
                    text = 'Adding...';
                }

                if (button.tagName === 'INPUT') {
                    button.value = text;
                } else {
                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>' +
                        text;
                }
            });
        });
    });
   
});

/* ==========================================
   Page Transition
========================================== */

document.querySelectorAll('a[href]').forEach(function (link) {
    link.addEventListener('click', function (event) {
        const href = link.getAttribute('href');

        if (
            !href ||
            href.startsWith('#') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            link.target === '_blank' ||
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return;
        }

        const currentUrl = new URL(window.location.href);
        const targetUrl = new URL(link.href, window.location.origin);

        if (currentUrl.origin !== targetUrl.origin) {
            return;
        }

        const content = document.querySelector('.lp-content');

        if (!content) {
            return;
        }

        event.preventDefault();

        content.style.opacity = '0';
        content.style.transform = 'translateY(6px)';
        content.style.transition = 'opacity .16s ease, transform .16s ease';

        window.setTimeout(function () {
            window.location.href = targetUrl.href;
        }, 160);
    });
});

/* ==========================================
   Keyboard Shortcuts
========================================== */

document.addEventListener("keydown", function (event) {

    const activeElement = document.activeElement;
    const typing =
        activeElement &&
        (
            activeElement.tagName === "INPUT" ||
            activeElement.tagName === "TEXTAREA" ||
            activeElement.tagName === "SELECT"
        );

    // Ctrl + K
    if (event.ctrlKey && event.key.toLowerCase() === "k") {

        event.preventDefault();

        const search = document.getElementById("globalSearch");

        if (search) {
            search.focus();
            search.select();
        }

        return;
    }

    // /
    if (!typing && event.key === "/") {

        event.preventDefault();

        const search = document.getElementById("globalSearch");

        if (search) {
            search.focus();
        }

        return;
    }

    // Alt + C
    if (event.altKey && event.key.toLowerCase() === "c") {

        event.preventDefault();

        window.location.href = "/companies/create";

        return;
    }

    // Alt + A
    if (event.altKey && event.key.toLowerCase() === "a") {

        event.preventDefault();

        window.location.href = "/calendar";

        return;
    }

    // ESC
    if (event.key === "Escape") {

        const results = document.getElementById("searchResults");

        if (results) {

            results.style.display = "none";

            results.innerHTML = "";

        }

    }



});