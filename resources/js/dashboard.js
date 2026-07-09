import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('dashboardChart');

    document.querySelectorAll('[data-count]').forEach((el) => {
        const target = parseInt(el.dataset.count || '0', 10);
        let current = 0;
        const step = Math.max(1, Math.ceil(target / 40));

        const timer = setInterval(() => {
            current += step;

            if (current >= target) {
                current = target;
                clearInterval(timer);
            }

            el.textContent = current.toLocaleString();
        }, 20);
    });

    if (!canvas) {
        return;
    }

    const stats = window.dashboardStats || {};

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['Companies', 'Contacts', 'Tasks', 'Meetings', 'Calls'],
            datasets: [{
                label: 'CRM Activity',
                data: [
                    stats.companies || 0,
                    stats.contacts || 0,
                    stats.tasks || 0,
                    stats.meetings || 0,
                    stats.calls || 0
                ],
                backgroundColor: [
                    '#2563EB',
                    '#7C3AED',
                    '#F59E0B',
                    '#10B981',
                    '#EF4444'
                ],
                borderRadius: 16
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#EEF2F7' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});