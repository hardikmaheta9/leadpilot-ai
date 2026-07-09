import './bootstrap';
import './search';
import './dashboard';

document.addEventListener('DOMContentLoaded', function () {

    const quickBtn = document.getElementById('quickAddToggle');
    const quickMenu = document.getElementById('quickAddMenu');

    if (quickBtn && quickMenu) {
        quickBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            quickMenu.classList.toggle('show');
        });
    }

    const notificationBtn = document.getElementById('notificationToggle');
    const notificationPanel = document.getElementById('notificationPanel');

    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            notificationPanel.classList.toggle('show');
        });
    }

    document.addEventListener('click', function (e) {
        if (quickBtn && quickMenu && !quickBtn.contains(e.target) && !quickMenu.contains(e.target)) {
            quickMenu.classList.remove('show');
        }

        if (notificationBtn && notificationPanel && !notificationBtn.contains(e.target) && !notificationPanel.contains(e.target)) {
            notificationPanel.classList.remove('show');
        }
    });

});