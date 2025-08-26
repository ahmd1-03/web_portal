/**
 * Enhanced Time Ago Utility for real-time relative time updates
 * Provides accurate relative time formatting with live updates
 */

class TimeAgo {
    constructor() {
        this.updateInterval = null;
        this.refreshRate = 30000; // 30 seconds
    }

    /**
     * Format timestamp to relative time with Indonesian localization
     * @param {string} timestamp - ISO timestamp string
     * @returns {string} Formatted relative time
     */
    format(timestamp) {
        if (!timestamp) return '';
        
        const now = new Date();
        const past = new Date(timestamp);
        const diffMs = now - past;
        
        if (diffMs < 0) return 'baru saja';
        
        const seconds = Math.floor(diffMs / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const weeks = Math.floor(days / 7);
        const months = Math.floor(days / 30);
        const years = Math.floor(days / 365);
        
        if (seconds < 5) return 'baru saja';
        if (seconds < 60) return `${seconds} detik yang lalu`;
        if (minutes < 60) return `${minutes} menit yang lalu`;
        if (hours < 24) return `${hours} jam yang lalu`;
        if (days < 7) return `${days} hari yang lalu`;
        if (weeks < 4) return `${weeks} minggu yang lalu`;
        if (months < 12) return `${months} bulan yang lalu`;
        return `${years} tahun yang lalu`;
    }

    /**
     * Update all time elements on the page
     */
    updateAll() {
        document.querySelectorAll('[data-time-ago]').forEach(element => {
            const timestamp = element.getAttribute('data-timestamp');
            if (timestamp) {
                element.textContent = this.format(timestamp);
            }
        });
    }

    /**
     * Start real-time updates
     */
    startRealTimeUpdates() {
        this.updateAll();
        this.updateInterval = setInterval(() => {
            this.updateAll();
        }, this.refreshRate);
    }

    /**
     * Stop updates
     */
    stopUpdates() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    }

    /**
     * Initialize on page load
     */
    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.startRealTimeUpdates());
        } else {
            this.startRealTimeUpdates();
        }

        // Update when page becomes visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) this.updateAll();
        });
    }
}

// Create global instance
window.timeAgo = new TimeAgo();
window.timeAgo.init();

export default TimeAgo;
