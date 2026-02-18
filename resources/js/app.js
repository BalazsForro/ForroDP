import './bootstrap';
import * as bootstrap from 'bootstrap';
import Chart from 'chart.js/auto';

// ── Statistics charts ────────────────────────────────────────────────────────

const statsCharts = {};
let pendingStatChartData = null;

const CHART_COLORS = [
    '#0d6efd', '#198754', '#dc3545', '#fd7e14',
    '#0dcaf0', '#6f42c1', '#d63384', '#20c997',
];

function hexToRgba(hex, alpha) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

function clearStatisticsCharts() {
    Object.values(statsCharts).forEach(chart => chart.destroy());
    for (const key in statsCharts) delete statsCharts[key];
}

function initStatisticsCharts(data) {
    Object.entries(data).forEach(([sensorId, chartData], index) => {
        const canvas = document.getElementById(`chart-sensor-${sensorId}`);
        if (!canvas) return;

        const color = CHART_COLORS[index % CHART_COLORS.length];
        const count = chartData.values.length;
        const pointRadius = count > 200 ? 0 : count > 50 ? 1 : 3;

        statsCharts[sensorId] = new Chart(canvas, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: chartData.unit
                        ? `${chartData.sensor_name} (${chartData.unit})`
                        : chartData.sensor_name,
                    data: chartData.values,
                    borderColor: color,
                    backgroundColor: hexToRgba(color, 0.1),
                    borderWidth: 2,
                    pointRadius,
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.parsed.y}${chartData.unit ? ' ' + chartData.unit : ''}`,
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            maxTicksLimit: 8,
                            color: '#6c757d',
                            font: { size: 11 },
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                    },
                    y: {
                        ticks: { color: '#6c757d', font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                    },
                },
            },
        });
    });
}

document.addEventListener('shown.bs.modal', (e) => {
    if (e.target.id !== 'statisticsModal') return;

    if (pendingStatChartData) {
        clearStatisticsCharts();
        initStatisticsCharts(pendingStatChartData);
        pendingStatChartData = null;
    }

    Object.values(statsCharts).forEach(chart => chart.resize());
});

document.addEventListener('hidden.bs.modal', (e) => {
    if (e.target.id === 'statisticsModal') {
        clearStatisticsCharts();
    }
});

// ── Livewire init ────────────────────────────────────────────────────────────

document.addEventListener('livewire:init', () => {
    Livewire.on('bs-modal-open', ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        bootstrap.Modal.getOrCreateInstance(el, { backdrop: 'static', keyboard: false }).show();
    });

    Livewire.on('bs-modal-close', ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        bootstrap.Modal.getOrCreateInstance(el).hide();
    });

    Livewire.on('bs-enable-tooltips', () => {
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
            .forEach(el => new bootstrap.Tooltip(el));
    });

    Livewire.on('bs-show-token', ({ token }) => {
        const el = document.getElementById('tokenModal');
        if (!el) return;

        const tokenEl = el.querySelector('#token');
        if (tokenEl) tokenEl.value = token;

        el.querySelector('#copyTokenBtn')?.addEventListener('click', () => {
            const val = document.getElementById('token')?.value;
            if (val) {
                navigator.clipboard.writeText(val);
                setTimeout(() => Livewire.dispatch('bs-toast-show', { message: 'Token copied to clipboard' }), 500);
            }
        });

        bootstrap.Modal.getOrCreateInstance(el, { backdrop: 'static', keyboard: false }).show();
    });

    Livewire.on('statistics-chart-data', ({ data }) => {
        const modal = document.getElementById('statisticsModal');
        if (modal?.classList.contains('show')) {
            // Modal already open — Livewire already updated the DOM (range change).
            // Destroy old instances (attached to the just-replaced canvases) then reinit.
            clearStatisticsCharts();
            initStatisticsCharts(data);
        } else {
            // Modal still opening — defer until shown.bs.modal fires (canvas guaranteed ready).
            pendingStatChartData = data;
        }
    });

    initToast();
});

// ── Toast ────────────────────────────────────────────────────────────────────

function initToast() {
    let toastCreatedAt = null;
    let toastInterval = null;

    function formatTimeAgo(seconds) {
        if (seconds < 60) return `${seconds}s ago`;
        return `${Math.floor(seconds / 60)}m ago`;
    }

    Livewire.on('bs-toast-show', ({ message }) => {
        const el = document.getElementById('mainToast');
        if (!el) return;

        document.getElementById('toastBody').textContent = message;

        toastCreatedAt = Date.now();
        document.getElementById('toastTime').textContent = 'just now';

        if (toastInterval) clearInterval(toastInterval);

        toastInterval = setInterval(() => {
            const diff = Math.floor((Date.now() - toastCreatedAt) / 1000);
            document.getElementById('toastTime').textContent = formatTimeAgo(diff);
        }, 1000);

        bootstrap.Toast.getOrCreateInstance(el, { delay: 5000 }).show();

        el.addEventListener('hidden.bs.toast', () => {
            clearInterval(toastInterval);
            toastInterval = null;
        }, { once: true });
    });
}
