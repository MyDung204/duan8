// Admin entrypoint. Lazy-load heavy libs only when needed.

function onIdle(cb) {
    if (window.requestIdleCallback) { requestIdleCallback(cb, { timeout: 1500 }); }
    else { setTimeout(cb, 300); }
}

function needsCharts() {
    return document.getElementById('postsChart') || document.getElementById('categoryChart');
}

async function ensureChartJs() {
    if (window.Chart) return window.Chart;
    const mod = await import('chart.js/auto');
    window.Chart = mod.default || mod;
    document.dispatchEvent(new CustomEvent('chartjs:ready'));
    return window.Chart;
}

document.addEventListener('DOMContentLoaded', () => {
    onIdle(() => {
        if (needsCharts()) {
            ensureChartJs();
        }
    });
});


