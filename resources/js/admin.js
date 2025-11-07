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

// Đảm bảo mặc định là dark mode cho admin
document.addEventListener('DOMContentLoaded', () => {
    // Kiểm tra nếu chưa có preference trong localStorage, đặt mặc định là dark
    if (!localStorage.getItem('flux-appearance')) {
        localStorage.setItem('flux-appearance', 'dark');
    }
    
    // Đảm bảo HTML có class dark
    if (!document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.add('dark');
    }
    
    // Nếu Flux UI đã load, đảm bảo nó sử dụng dark mode
    if (window.$flux && window.$flux.appearance !== 'dark') {
        window.$flux.appearance = 'dark';
    }
    
    onIdle(() => {
        if (needsCharts()) {
            ensureChartJs();
        }
    });
});


