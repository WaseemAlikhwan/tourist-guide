/**
 * Destination Show Page JavaScript
 * Professional and optimized code
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        initFavoriteButton();
        initWeatherUpdate();
        initLazyLoading();
        initSmoothScroll();
    });

    /**
     * Initialize Leaflet Map
     */
    function initMap() {
        const mapElement = document.getElementById('map');
        if (!mapElement) return;

        const latitude = parseFloat(mapElement.dataset.latitude);
        const longitude = parseFloat(mapElement.dataset.longitude);
        const destinationName = mapElement.dataset.name || 'الوجهة';

        if (!latitude || !longitude) return;

        // Check if Leaflet is loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded');
            return;
        }

        try {
            // Initialize map
            const map = L.map('map').setView([latitude, longitude], 13);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add marker with custom icon
            const icon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            L.marker([latitude, longitude], { icon: icon })
                .addTo(map)
                .bindPopup(`<strong>${destinationName}</strong>`)
                .openPopup();
        } catch (error) {
            console.error('Error initializing map:', error);
            mapElement.innerHTML = '<p class="text-center p-4">حدث خطأ في تحميل الخريطة</p>';
        }
    }

    /**
     * Initialize Favorite Button
     */
    function initFavoriteButton() {
        const favoriteBtn = document.getElementById('favorite-btn');
        if (!favoriteBtn) return;

        favoriteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            handleFavoriteToggle(this);
        });
    }

    /**
     * Handle Favorite Toggle
     */
    async function handleFavoriteToggle(button) {
        const type = button.dataset.type;
        const id = button.dataset.id;
        const toggleUrl = button.dataset.toggleUrl;

        if (!type || !id || !toggleUrl) {
            showError('معلومات غير صحيحة');
            return;
        }

        // Disable button during request
        button.disabled = true;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<span class="spinner"></span> جاري المعالجة...';

        try {
            const response = await fetch(toggleUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    favoritable_type: type,
                    favoritable_id: id
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'حدث خطأ في العملية');
            }

            if (data.success) {
                updateFavoriteButton(button, data.isFavorited);
                showSuccess(data.message || (data.isFavorited ? 'تمت الإضافة للمفضلة' : 'تم الحذف من المفضلة'));
            } else {
                throw new Error(data.message || 'فشلت العملية');
            }
        } catch (error) {
            console.error('Error toggling favorite:', error);
            showError(error.message || 'حدث خطأ أثناء تحديث المفضلة');
            button.innerHTML = originalHTML;
        } finally {
            button.disabled = false;
        }
    }

    /**
     * Update Favorite Button State
     */
    function updateFavoriteButton(button, isFavorited) {
        if (isFavorited) {
            button.classList.add('active');
            button.style.background = '#ef4444';
            button.style.color = '#fff';
            const favoriteText = document.getElementById('favorite-text');
            if (favoriteText) {
                favoriteText.textContent = 'في المفضلة';
            }
            
            const svg = button.querySelector('svg');
            if (svg) {
                svg.setAttribute('fill', 'currentColor');
            }
        } else {
            button.classList.remove('active');
            button.style.background = '#f1f5f9';
            button.style.color = '#475569';
            const favoriteText = document.getElementById('favorite-text');
            if (favoriteText) {
                favoriteText.textContent = 'إضافة للمفضلة';
            }
            
            const svg = button.querySelector('svg');
            if (svg) {
                svg.setAttribute('fill', 'none');
            }
        }
    }

    /**
     * Initialize Weather Update
     */
    function initWeatherUpdate() {
        // Main weather update button
        const updateBtn = document.getElementById('update-weather-btn');
        if (updateBtn) {
            updateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                updateWeather(this);
            });
        }

        // Retry weather update button (shown when weather fails to load)
        const retryBtn = document.getElementById('update-weather-btn-retry');
        if (retryBtn) {
            retryBtn.addEventListener('click', function(e) {
                e.preventDefault();
                updateWeather(this);
            });
        }
    }

    /**
     * Update Weather Information
     */
    async function updateWeather(button) {
        const updateUrl = button.dataset.updateUrl;
        if (!updateUrl) {
            showError('رابط التحديث غير متوفر');
            return;
        }

        // Disable button during request
        button.disabled = true;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<span class="spinner"></span> جاري التحديث...';

        try {
            const response = await fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'حدث خطأ أثناء تحديث الطقس');
            }

            if (data.success) {
                showSuccess('تم تحديث معلومات الطقس بنجاح');
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'فشل تحديث الطقس');
            }
        } catch (error) {
            console.error('Error updating weather:', error);
            showError(error.message || 'حدث خطأ أثناء تحديث الطقس');
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    }

    /**
     * Initialize Lazy Loading for Images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            img.classList.add('loaded');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Initialize Smooth Scroll
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Get CSRF Token
     */
    function getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Show Success Message
     */
    function showSuccess(message) {
        showNotification(message, 'success');
    }

    /**
     * Show Error Message
     */
    function showError(message) {
        showNotification(message, 'error');
    }

    /**
     * Show Notification
     */
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelector('.custom-notification');
        if (existing) {
            existing.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `custom-notification alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show`;
        notification.style.cssText = 'position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px; max-width: 90%;';
        notification.setAttribute('role', 'alert');
        notification.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

})();
