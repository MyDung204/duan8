import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Đảm bảo mặc định là dark mode
document.addEventListener('DOMContentLoaded', function() {
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
});

Alpine.start();
