import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================
       Animated KPI Counters
    ========================================== */

    const counters = document.querySelectorAll('[data-counter]');

    counters.forEach(function (element) {

        const target = Number.parseInt(
            element.dataset.counterValue ||
            element.dataset.counter ||
            element.textContent ||
            '0',
            10
        );

        if (!Number.isFinite(target)) {
            return;
        }

        const duration = 900;
        const startTime = performance.now();

        function animateCounter(currentTime) {

            const progress = Math.min(
                (currentTime - startTime) / duration,
                1
            );

            const eased = 1 - Math.pow(1 - progress, 3);

            const current = Math.round(target * eased);

            element.textContent = current.toLocaleString();

            if (progress < 1) {
                window.requestAnimationFrame(animateCounter);
            } else {
                element.textContent = target.toLocaleString();
            }
        }

        window.requestAnimationFrame(animateCounter);

    });


    /* ==========================================
       Premium Business Overview Chart
    ========================================== */

    const chartCanvas = document.getElementById('dashboardChart');

    if (!chartCanvas || !window.dashboardStats) {
        return;
    }

    const labels = Array.isArray(window.dashboardStats.labels)
        ? window.dashboardStats.labels
        : [];

    const values = Array.isArray(window.dashboardStats.data)
        ? window.dashboardStats.data.map(function (value) {
            return Number(value) || 0;
        })
        : [];

    if (!labels.length || !values.length) {
        return;
    }

    const context = chartCanvas.getContext('2d');

    if (!context) {
        return;
    }

    // Destroy old chart if it exists
    const oldChart = Chart.getChart(chartCanvas);

    if (oldChart) {
        oldChart.destroy();
    }

    const gradient = context.createLinearGradient(
        0,
        0,
        0,
        chartCanvas.parentElement?.clientHeight || 340
    );

    gradient.addColorStop(0, 'rgba(37,99,235,.35)');
    gradient.addColorStop(.55, 'rgba(124,58,237,.15)');
    gradient.addColorStop(1, 'rgba(255,255,255,0)');

    new Chart(context, {

        type: 'line',

        data: {

            labels: labels,

            datasets: [

                {

                    label: 'CRM Records',

                    data: values,

                    fill: true,

                    backgroundColor: gradient,

                    borderColor: '#2563EB',

                    borderWidth: 3,

                    tension: .42,

                    cubicInterpolationMode: 'monotone',

                    pointRadius: 5,

                    pointHoverRadius: 8,

                    pointHitRadius: 18,

                    pointBackgroundColor: '#FFFFFF',

                    pointBorderColor: '#2563EB',

                    pointBorderWidth: 3,

                    pointHoverBackgroundColor: '#7C3AED',

                    pointHoverBorderColor: '#FFFFFF',

                    pointHoverBorderWidth: 3,

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            resizeDelay: 100,

            animation: {

                duration: 1200,

                easing: 'easeOutQuart'

            },

            interaction: {

                intersect: false,

                mode: 'index'

            },

            plugins: {

                legend: {

                    display: false

                },

                tooltip: {

                    displayColors: false,

                    backgroundColor: '#0F172A',

                    titleColor: '#fff',

                    bodyColor: '#fff',

                    padding: 14,

                    cornerRadius: 12,

                    callbacks: {

                        label: function(context){

                            return context.parsed.y.toLocaleString() + ' records';

                        }

                    }

                }

            },

            scales: {

                x: {

                    border: {

                        display: false

                    },

                    grid: {

                        display: false

                    },

                    ticks: {

                        color: '#64748B',

                        font: {

                            size: 11,

                            weight: '700'

                        }

                    }

                },

                y: {

                    beginAtZero: true,

                    suggestedMax: Math.max(...values, 5) + 1,

                    border: {

                        display: false

                    },

                    grid: {

                        color: 'rgba(226,232,240,.8)'

                    },

                    ticks: {

                        precision: 0,

                        color: '#94A3B8',

                        font: {

                            size: 11,

                            weight: '600'

                        },

                        callback: function(value){

                            return Number(value).toLocaleString();

                        }

                    }

                }

            }

        }

    });

});

/* ==========================================
   Sprint 10.2 - AI Analytics Charts
========================================== */

document.addEventListener('DOMContentLoaded', function () {

    if (typeof Chart === 'undefined') {
        return;
    }

    if (!window.aiCharts) {
        return;
    }

    function destroyChart(canvas) {

        if (!canvas) {
            return;
        }

        const existing = Chart.getChart(canvas);

        if (existing) {
            existing.destroy();
        }

    }

    function createDoughnutChart(
        canvasId,
        chartData,
        colors
    ) {

        const canvas =
            document.getElementById(canvasId);

        if (!canvas || !chartData) {
            return;
        }

        destroyChart(canvas);

        new Chart(canvas, {

            type: 'doughnut',

            data: {

                labels: chartData.labels || [],

                datasets: [

                    {

                        data: chartData.data || [],

                        backgroundColor: colors,

                        borderColor: '#ffffff',

                        borderWidth: 2,

                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '68%',

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {

                            usePointStyle: true,

                            boxWidth: 10

                        }

                    }

                }

            }

        });

    }

        function createBarChart(
        canvasId,
        chartData,
        color
    ) {

        const canvas =
            document.getElementById(canvasId);

        if (!canvas || !chartData) {
            return;
        }

        destroyChart(canvas);

        new Chart(canvas, {

            type: 'bar',

            data: {

                labels: chartData.labels || [],

                datasets: [

                    {

                        data: chartData.data || [],

                        backgroundColor: color,

                        borderRadius: 8,

                        borderSkipped: false,

                        maxBarThickness: 34,

                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                },

                scales: {

                    x: {

                        grid: {

                            display: false

                        },

                        border: {

                            display: false

                        },

                        ticks: {

                            color: '#64748B',

                            font: {

                                size: 11,

                                weight: '700'

                            }

                        }

                    },

                    y: {

                        beginAtZero: true,

                        border: {

                            display: false

                        },

                        grid: {

                            color: 'rgba(226,232,240,.75)'

                        },

                        ticks: {

                            precision: 0,

                            color: '#94A3B8'

                        }

                    }

                }

            }

        });

    }


        createDoughnutChart(

        'leadGradeChart',

        window.aiCharts.leadGrade,

        [

            '#10B981',

            '#2563EB',

            '#F59E0B',

            '#F97316',

            '#EF4444'

        ]

    );

    createDoughnutChart(

        'websiteHealthChart',

        window.aiCharts.websiteHealth,

        [

            '#10B981',

            '#3B82F6',

            '#F59E0B',

            '#EF4444'

        ]

    );

    createBarChart(

        'technologyChart',

        window.aiCharts.technology,

        'rgba(37,99,235,.85)'

    );

    createBarChart(

        'industryChart',

        window.aiCharts.industry,

        'rgba(124,58,237,.85)'

    );

});