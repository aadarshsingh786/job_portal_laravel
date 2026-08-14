import './bootstrap';
import Alpine from 'alpinejs';
import axios from 'axios';

// Initialize Alpine
window.Alpine = Alpine;
Alpine.start();

// Global Axios configuration
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;

// Auto-close flash messages
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 1s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 1000);
        }, 5000);
    });
});

// Job Application AJAX
window.applyToJob = async function(jobId) {
    try {
        const response = await axios.post(`/jobs/${jobId}/apply`);
        if (response.data.success) {
            document.getElementById('apply-btn').innerHTML = '<i class="fas fa-check"></i> Applied';
            document.getElementById('apply-btn').className = 'bg-green-500 text-white px-6 py-2 rounded-lg cursor-default';
            showToast('Application submitted successfully!', 'success');
        }
    } catch (error) {
        showToast(error.response?.data?.message || 'Error applying for job', 'error');
    }
};

// Save/Unsave Job
window.toggleSaveJob = async function(jobId) {
    try {
        const isSaved = document.getElementById(`save-btn-${jobId}`).dataset.saved === 'true';
        const method = isSaved ? 'delete' : 'post';
        const url = isSaved ? `/jobs/${jobId}/unsave` : `/jobs/${jobId}/save`;
        
        const response = await axios({
            method: method,
            url: url
        });
        
        if (response.data.success) {
            const btn = document.getElementById(`save-btn-${jobId}`);
            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');
            
            if (isSaved) {
                icon.className = 'far fa-bookmark';
                text.textContent = 'Save';
                btn.dataset.saved = 'false';
            } else {
                icon.className = 'fas fa-bookmark';
                text.textContent = 'Saved';
                btn.dataset.saved = 'true';
            }
            showToast(response.data.message, 'success');
        }
    } catch (error) {
        showToast('Error saving job', 'error');
    }
};

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };
    
    toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition duration-500 ease-in-out`;
    toast.style.transform = 'translateY(100px)';
    toast.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(100px)';
        setTimeout(() => toast.remove(), 500);
    }, 5000);
}

// Live search with debounce
let searchTimeout;
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    }
});

// Dynamic job type filter
document.addEventListener('DOMContentLoaded', function() {
    const jobTypeFilter = document.querySelector('select[name="job_type"]');
    if (jobTypeFilter) {
        jobTypeFilter.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }
});

// Scroll reveal animations
document.addEventListener('DOMContentLoaded', function() {
    const revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length === 0) return;

    if (!('IntersectionObserver' in window)) {
        revealEls.forEach(el => el.classList.add('reveal-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach(el => observer.observe(el));
});

// Animated number counters
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length === 0) return;

    const animate = (el) => {
        const target = parseInt(el.dataset.counter, 10);
        const duration = 1800;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            const value = Math.round(target * ease);
            el.textContent = value.toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };

    if (!('IntersectionObserver' in window)) {
        counters.forEach(animate);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animate(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    counters.forEach(el => observer.observe(el));
});