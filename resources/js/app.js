import './bootstrap';
import './search';
import './dashboard';

document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================
       Shared Helpers
    ========================================== */

    const isTypingField = function (element) {
        return Boolean(
            element &&
            (
                element.tagName === 'INPUT' ||
                element.tagName === 'TEXTAREA' ||
                element.tagName === 'SELECT' ||
                element.isContentEditable
            )
        );
    };

    const isInternalNavigableLink = function (link, event = null) {
        if (!link) {
            return false;
        }

        const href = link.getAttribute('href');

        if (
            !href ||
            href.startsWith('#') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            href.startsWith('javascript:') ||
            link.hasAttribute('download') ||
            link.target === '_blank'
        ) {
            return false;
        }

        if (
            event &&
            (
                event.ctrlKey ||
                event.metaKey ||
                event.shiftKey ||
                event.altKey ||
                event.button === 1
            )
        ) {
            return false;
        }

        const currentUrl = new URL(window.location.href);
        const targetUrl = new URL(link.href, window.location.origin);

        return currentUrl.origin === targetUrl.origin;
    };

    /* ==========================================
       Runtime UX Styles
    ========================================== */

    if (!document.getElementById('lpRuntimeUxStyles')) {
        const style = document.createElement('style');

        style.id = 'lpRuntimeUxStyles';

        style.textContent = `
            .lp-page-loader{
                position:fixed;
                inset:0;
                z-index:30000;
                display:flex;
                align-items:center;
                justify-content:center;
                background:rgba(248,250,252,.78);
                backdrop-filter:blur(6px);
                opacity:0;
                visibility:hidden;
                transition:opacity .18s ease, visibility .18s ease;
            }

            .lp-page-loader.show{
                opacity:1;
                visibility:visible;
            }

            .lp-page-loader-card{
                display:flex;
                align-items:center;
                gap:12px;
                padding:16px 20px;
                border:1px solid #E2E8F0;
                border-radius:16px;
                background:#FFFFFF;
                color:#0F172A;
                font-size:13px;
                font-weight:800;
                box-shadow:0 18px 45px rgba(15,23,42,.12);
            }

            .lp-ripple-host{
                position:relative;
                overflow:hidden;
            }

            .lp-ripple-effect{
                position:absolute;
                border-radius:50%;
                pointer-events:none;
                background:rgba(255,255,255,.42);
                transform:scale(0);
                animation:lpRuntimeRipple .55s ease-out;
            }

            @keyframes lpRuntimeRipple{
                to{
                    transform:scale(4);
                    opacity:0;
                }
            }

            .lp-mobile-nav-toggle{
                display:none;
                width:42px;
                height:42px;
                border:1px solid #E2E8F0;
                border-radius:13px;
                background:#FFFFFF;
                color:#0F172A;
                align-items:center;
                justify-content:center;
                flex-shrink:0;
            }

            .lp-sidebar-overlay{
                position:fixed;
                inset:0;
                z-index:1090;
                background:rgba(15,23,42,.48);
                opacity:0;
                visibility:hidden;
                transition:opacity .2s ease, visibility .2s ease;
            }

            .lp-sidebar-overlay.show{
                opacity:1;
                visibility:visible;
            }

            .lp-copy-feedback{
                position:fixed;
                right:20px;
                bottom:20px;
                z-index:31000;
                padding:11px 14px;
                border-radius:12px;
                background:#0F172A;
                color:#FFFFFF;
                font-size:12px;
                font-weight:800;
                box-shadow:0 14px 34px rgba(15,23,42,.2);
                animation:lpFadeUp .2s ease both;
            }

            @media (max-width:991.98px){
                .lp-mobile-nav-toggle{
                    display:inline-flex;
                }

                .lp-sidebar{
                    position:fixed !important;
                    top:0;
                    bottom:0;
                    left:0;
                    z-index:1100;
                    transform:translateX(-105%);
                    transition:transform .24s ease;
                    overflow-y:auto;
                }

                body.lp-sidebar-open{
                    overflow:hidden;
                }

                body.lp-sidebar-open .lp-sidebar{
                    transform:translateX(0);
                }

                .lp-main{
                    margin-left:0 !important;
                    width:100% !important;
                }

                .lp-topbar{
                    gap:10px;
                }

                .lp-search-wrap{
                    min-width:0;
                    flex:1;
                }
            }
        `;

        document.head.appendChild(style);
    }

    /* ==========================================
       Global Page Loader
    ========================================== */

    const pageLoader = document.createElement('div');

    pageLoader.className = 'lp-page-loader';
    pageLoader.setAttribute('aria-hidden', 'true');

    pageLoader.innerHTML = `
        <div class="lp-page-loader-card">
            <span
                class="spinner-border spinner-border-sm"
                aria-hidden="true">
            </span>

            <span>Loading...</span>
        </div>
    `;

    document.body.appendChild(pageLoader);

    const showPageLoader = function () {
        pageLoader.classList.add('show');
        pageLoader.setAttribute('aria-hidden', 'false');
    };

    const hidePageLoader = function () {
        pageLoader.classList.remove('show');
        pageLoader.setAttribute('aria-hidden', 'true');
    };

    window.addEventListener('pageshow', hidePageLoader);

    /* ==========================================
       Mobile Sidebar
    ========================================== */

    const sidebar = document.querySelector('.lp-sidebar');
    const topbar = document.querySelector('.lp-topbar');

    if (sidebar && topbar) {
        let sidebarToggle =
            document.getElementById('lpMobileNavToggle');

        if (!sidebarToggle) {
            sidebarToggle = document.createElement('button');

            sidebarToggle.id = 'lpMobileNavToggle';
            sidebarToggle.type = 'button';
            sidebarToggle.className = 'lp-mobile-nav-toggle';

            sidebarToggle.setAttribute(
                'aria-label',
                'Open navigation'
            );

            sidebarToggle.setAttribute(
                'aria-expanded',
                'false'
            );

            sidebarToggle.innerHTML =
                '<i class="fa-solid fa-bars"></i>';

            topbar.prepend(sidebarToggle);
        }

        let sidebarOverlay =
            document.getElementById('lpSidebarOverlay');

        if (!sidebarOverlay) {
            sidebarOverlay = document.createElement('div');

            sidebarOverlay.id = 'lpSidebarOverlay';
            sidebarOverlay.className = 'lp-sidebar-overlay';

            document.body.appendChild(sidebarOverlay);
        }

        const closeSidebar = function () {
            document.body.classList.remove('lp-sidebar-open');

            sidebarOverlay.classList.remove('show');

            sidebarToggle.setAttribute(
                'aria-expanded',
                'false'
            );

            sidebarToggle.innerHTML =
                '<i class="fa-solid fa-bars"></i>';
        };

        const openSidebar = function () {
            document.body.classList.add('lp-sidebar-open');

            sidebarOverlay.classList.add('show');

            sidebarToggle.setAttribute(
                'aria-expanded',
                'true'
            );

            sidebarToggle.innerHTML =
                '<i class="fa-solid fa-xmark"></i>';
        };

        sidebarToggle.addEventListener(
            'click',
            function () {
                if (
                    document.body.classList.contains(
                        'lp-sidebar-open'
                    )
                ) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
        );

        sidebarOverlay.addEventListener(
            'click',
            closeSidebar
        );

        sidebar.querySelectorAll('a').forEach(
            function (link) {
                link.addEventListener(
                    'click',
                    function () {
                        if (window.innerWidth <= 991) {
                            closeSidebar();
                        }
                    }
                );
            }
        );

        window.addEventListener(
            'resize',
            function () {
                if (window.innerWidth > 991) {
                    closeSidebar();
                }
            }
        );
    }

    /* ==========================================
       Floating Quick Add
    ========================================== */

    const quickBtn =
        document.getElementById('quickAddToggle');

    const quickMenu =
        document.getElementById('quickAddMenu');

    if (quickBtn && quickMenu) {
        quickBtn.addEventListener(
            'click',
            function (event) {
                event.stopPropagation();

                quickMenu.classList.toggle('show');
            }
        );
    }

    /* ==========================================
       Notification Dropdown
    ========================================== */

    const notificationBtn =
        document.getElementById('notificationToggle');

    const notificationPanel =
        document.getElementById('notificationPanel');

    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener(
            'click',
            function (event) {
                event.stopPropagation();

                notificationPanel.classList.toggle('show');
            }
        );
    }
    
    /* ==========================================
       Auto-close Menus and Dropdowns
    ========================================== */

    document.addEventListener(
        'click',
        function (event) {

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

            document
                .querySelectorAll('.dropdown-menu.show')
                .forEach(function (menu) {

                    const dropdown =
                        menu.closest('.dropdown');

                    if (
                        dropdown &&
                        !dropdown.contains(event.target)
                    ) {
                        const toggle =
                            dropdown.querySelector(
                                '[data-bs-toggle="dropdown"]'
                            );

                        if (
                            toggle &&
                            window.bootstrap
                        ) {
                            window.bootstrap.Dropdown
                                .getOrCreateInstance(toggle)
                                .hide();
                        }
                    }
                });
        }
    );

    /* ==========================================
       Toast Notifications
    ========================================== */

    const toasts =
        document.querySelectorAll('[data-lp-toast]');

    const closeToast = function (toast) {

        if (
            !toast ||
            toast.classList.contains('is-hiding')
        ) {
            return;
        }

        toast.classList.add('is-hiding');

        window.setTimeout(
            function () {
                toast.remove();
            },
            300
        );
    };

    toasts.forEach(function (toast) {

        const closeButton =
            toast.querySelector(
                '[data-lp-toast-close]'
            );

        let dismissTimer = null;

        const startTimer = function () {

            dismissTimer = window.setTimeout(
                function () {
                    closeToast(toast);
                },
                4500
            );
        };

        const pauseTimer = function () {

            window.clearTimeout(dismissTimer);
        };

        if (closeButton) {

            closeButton.addEventListener(
                'click',
                function () {

                    pauseTimer();

                    closeToast(toast);
                }
            );
        }

        toast.addEventListener(
            'mouseenter',
            pauseTimer
        );

        toast.addEventListener(
            'mouseleave',
            startTimer
        );

        startTimer();
    });

    /* ==========================================
       Global Delete Confirmation
    ========================================== */

    let pendingDeleteForm = null;
    let deleteConfirmed = false;

    document.addEventListener(
        'submit',
        function (event) {

            const form =
                event.target.closest(
                    'form[data-confirm-delete]'
                );

            if (
                !form ||
                deleteConfirmed
            ) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            pendingDeleteForm = form;

            const messageElement =
                document.getElementById(
                    'deleteConfirmMessage'
                );

            if (messageElement) {

                messageElement.textContent =
                    form.dataset.confirmMessage ||
                    'This action cannot be undone.';
            }

            const modalElement =
                document.getElementById(
                    'deleteConfirmModal'
                );

            if (
                modalElement &&
                window.bootstrap
            ) {
                window.bootstrap.Modal
                    .getOrCreateInstance(
                        modalElement
                    )
                    .show();
            }
        },
        true
    );

    const confirmDeleteButton =
        document.getElementById(
            'confirmDeleteButton'
        );

    if (confirmDeleteButton) {

        confirmDeleteButton.addEventListener(
            'click',
            function () {

                if (!pendingDeleteForm) {
                    return;
                }

                const formToSubmit =
                    pendingDeleteForm;

                pendingDeleteForm = null;
                deleteConfirmed = true;

                const modalElement =
                    document.getElementById(
                        'deleteConfirmModal'
                    );

                if (
                    modalElement &&
                    window.bootstrap
                ) {
                    window.bootstrap.Modal
                        .getOrCreateInstance(
                            modalElement
                        )
                        .hide();
                }

                formToSubmit.removeAttribute(
                    'data-confirm-delete'
                );

                window.setTimeout(
                    function () {

                        formToSubmit.requestSubmit();
                    },
                    150
                );
            }
        );
    }

    /* ==========================================
       Loading Buttons and Form Loader
    ========================================== */

    document
        .querySelectorAll('form')
        .forEach(function (form) {

            form.addEventListener(
                'submit',
                function () {

                    if (
                        form.hasAttribute(
                            'data-confirm-delete'
                        ) &&
                        !deleteConfirmed
                    ) {
                        return;
                    }

                    const submitButtons =
                        form.querySelectorAll(
                            'button[type="submit"], input[type="submit"]'
                        );

                    submitButtons.forEach(
                        function (button) {

                            if (
                                button.dataset
                                    .loadingApplied
                            ) {
                                return;
                            }

                            button.dataset
                                .loadingApplied = '1';

                            button.disabled = true;

                            const original =
                                button.innerHTML ||
                                button.value ||
                                '';

                            button.dataset.original =
                                original;

                            const originalLower =
                                original.toLowerCase();

                            let text =
                                'Processing...';

                            if (
                                originalLower.includes(
                                    'save'
                                )
                            ) {
                                text = 'Saving...';

                            } else if (
                                originalLower.includes(
                                    'update'
                                )
                            ) {
                                text = 'Updating...';

                            } else if (
                                originalLower.includes(
                                    'upload'
                                )
                            ) {
                                text = 'Uploading...';

                            } else if (
                                originalLower.includes(
                                    'delete'
                                )
                            ) {
                                text = 'Deleting...';

                            } else if (
                                originalLower.includes(
                                    'complete'
                                )
                            ) {
                                text = 'Completing...';

                            } else if (
                                originalLower.includes(
                                    'add'
                                )
                            ) {
                                text = 'Adding...';
                            }

                            if (
                                button.tagName ===
                                'INPUT'
                            ) {
                                button.value = text;

                            } else {
                                button.innerHTML =
                                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                                    text;
                            }
                        }
                    );

                    if (
                        !form.hasAttribute(
                            'target'
                        )
                    ) {
                        window.setTimeout(
                            showPageLoader,
                            80
                        );
                    }
                }
            );
        });

    /* ==========================================
       Modal Autofocus
    ========================================== */

    document.addEventListener(
        'shown.bs.modal',
        function (event) {

            const modal = event.target;

            const firstField =
                modal.querySelector(
                    '[autofocus], input:not([type="hidden"]):not([disabled]), textarea:not([disabled]), select:not([disabled])'
                );

            if (firstField) {

                window.setTimeout(
                    function () {

                        firstField.focus({
                            preventScroll: true
                        });
                    },
                    80
                );
            }
        }
    );

    /* ==========================================
       Ripple Effect
    ========================================== */

    document
        .querySelectorAll(
            '.lp-btn, .btn, .lp-icon-btn, .lp-action-card, .lp-contact-quick-btn, .lp-task-action-btn, .lp-meeting-action-btn, .lp-call-action-btn, .lp-document-action-btn'
        )
        .forEach(function (element) {

            element.classList.add(
                'lp-ripple-host'
            );

            element.addEventListener(
                'pointerdown',
                function (event) {

                    const rect =
                        element.getBoundingClientRect();

                    const size =
                        Math.max(
                            rect.width,
                            rect.height
                        );

                    const ripple =
                        document.createElement(
                            'span'
                        );

                    ripple.className =
                        'lp-ripple-effect';

                    ripple.style.width =
                        `${size}px`;

                    ripple.style.height =
                        `${size}px`;

                    ripple.style.left =
                        `${event.clientX -
                        rect.left -
                        size / 2}px`;

                    ripple.style.top =
                        `${event.clientY -
                        rect.top -
                        size / 2}px`;

                    element.appendChild(
                        ripple
                    );

                    window.setTimeout(
                        function () {
                            ripple.remove();
                        },
                        600
                    );
                }
            );
        });

    /* ==========================================
       Copy to Clipboard Helper
    ========================================== */

    const showCopyFeedback =
        function (message) {

            const oldFeedback =
                document.querySelector(
                    '.lp-copy-feedback'
                );

            if (oldFeedback) {
                oldFeedback.remove();
            }

            const feedback =
                document.createElement('div');

            feedback.className =
                'lp-copy-feedback';

            feedback.textContent =
                message;

            document.body.appendChild(
                feedback
            );

            window.setTimeout(
                function () {
                    feedback.remove();
                },
                1800
            );
        };

    document
        .querySelectorAll(
            '[data-copy-text], [data-copy-target]'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                async function () {

                    let value =
                        button.dataset.copyText ||
                        '';

                    if (
                        !value &&
                        button.dataset.copyTarget
                    ) {
                        const target =
                            document.querySelector(
                                button.dataset
                                    .copyTarget
                            );

                        value =
                            target?.value ||
                            target?.textContent
                                ?.trim() ||
                            '';
                    }

                    if (!value) {
                        return;
                    }

                    try {

                        await navigator
                            .clipboard
                            .writeText(value);

                        showCopyFeedback(
                            'Copied to clipboard'
                        );

                    } catch (error) {

                        showCopyFeedback(
                            'Unable to copy'
                        );
                    }
                }
            );
        });
            /* ==========================================
       Lazy Images
    ========================================== */

    document
        .querySelectorAll('img:not([loading])')
        .forEach(function (image) {

            image.loading = 'lazy';
            image.decoding = 'async';
        });

    /* ==========================================
       Smooth Anchor Scrolling
    ========================================== */

    document
        .querySelectorAll('a[href^="#"]')
        .forEach(function (link) {

            link.addEventListener(
                'click',
                function (event) {

                    const selector =
                        link.getAttribute('href');

                    if (
                        !selector ||
                        selector === '#'
                    ) {
                        return;
                    }

                    const target =
                        document.querySelector(
                            selector
                        );

                    if (!target) {
                        return;
                    }

                    event.preventDefault();

                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            );
        });

    /* ==========================================
       Scroll Position Memory
    ========================================== */

    const scrollKey =
        `lp-scroll:${window.location.pathname}${window.location.search}`;

    const savedScroll =
        sessionStorage.getItem(scrollKey);

    if (savedScroll !== null) {

        window.requestAnimationFrame(
            function () {

                window.scrollTo({
                    top:
                        Number(savedScroll) ||
                        0,
                    behavior: 'auto'
                });
            }
        );
    }

    window.addEventListener(
        'beforeunload',
        function () {

            sessionStorage.setItem(
                scrollKey,
                String(window.scrollY)
            );
        }
    );

    /* ==========================================
       Remember Company Tab
    ========================================== */

    document
        .querySelectorAll(
            '.lp-company-tabs a'
        )
        .forEach(function (link) {

            link.addEventListener(
                'click',
                function () {

                    const targetUrl =
                        new URL(
                            link.href,
                            window.location.origin
                        );

                    const tab =
                        targetUrl.searchParams
                            .get('tab');

                    if (tab) {

                        sessionStorage.setItem(
                            'lp-company-last-tab',
                            tab
                        );
                    }
                }
            );
        });

    /* ==========================================
       Page Transition
    ========================================== */

    document
        .querySelectorAll('a[href]')
        .forEach(function (link) {

            link.addEventListener(
                'click',
                function (event) {

                    if (
                        !isInternalNavigableLink(
                            link,
                            event
                        )
                    ) {
                        return;
                    }

                    const targetUrl =
                        new URL(
                            link.href,
                            window.location.origin
                        );

                    const currentUrl =
                        new URL(
                            window.location.href
                        );

                    if (
                        targetUrl.pathname ===
                            currentUrl.pathname &&
                        targetUrl.search ===
                            currentUrl.search &&
                        targetUrl.hash
                    ) {
                        return;
                    }

                    const content =
                        document.querySelector(
                            '.lp-content'
                        );

                    event.preventDefault();

                    showPageLoader();

                    if (content) {

                        content.style.opacity =
                            '0';

                        content.style.transform =
                            'translateY(6px)';

                        content.style.transition =
                            'opacity .14s ease, transform .14s ease';
                    }

                    window.setTimeout(
                        function () {

                            window.location.href =
                                targetUrl.href;
                        },
                        140
                    );
                }
            );
        });

    /* ==========================================
       Keyboard Shortcuts
    ========================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            const typing =
                isTypingField(
                    document.activeElement
                );

            if (
                (
                    event.ctrlKey ||
                    event.metaKey
                ) &&
                event.key.toLowerCase() ===
                    'k'
            ) {
                event.preventDefault();

                const search =
                    document.getElementById(
                        'globalSearch'
                    );

                if (search) {

                    search.focus();
                    search.select();
                }

                return;
            }

            if (
                !typing &&
                event.key === '/'
            ) {
                event.preventDefault();

                const search =
                    document.getElementById(
                        'globalSearch'
                    );

                if (search) {

                    search.focus();
                }

                return;
            }

            if (
                event.altKey &&
                event.key.toLowerCase() ===
                    'c'
            ) {
                event.preventDefault();

                window.location.href =
                    '/companies/create';

                return;
            }

            if (
                event.altKey &&
                event.key.toLowerCase() ===
                    'a'
            ) {
                event.preventDefault();

                window.location.href =
                    '/calendar';

                return;
            }

            if (event.key === 'Escape') {

                const results =
                    document.getElementById(
                        'searchResults'
                    );

                if (results) {

                    results.style.display =
                        'none';

                    results.innerHTML = '';
                }

                if (quickMenu) {
                    quickMenu.classList.remove(
                        'show'
                    );
                }

                if (notificationPanel) {
                    notificationPanel
                        .classList
                        .remove('show');
                }

                if (
                    document.body.classList
                        .contains(
                            'lp-sidebar-open'
                        )
                ) {
                    document.body.classList
                        .remove(
                            'lp-sidebar-open'
                        );

                    const sidebarOverlay =
                        document.getElementById(
                            'lpSidebarOverlay'
                        );

                    if (sidebarOverlay) {

                        sidebarOverlay
                            .classList
                            .remove('show');
                    }
                }
            }
        }
    );

});