/**
 * Corrected Time Ago Utility with proper timezone handling
 * Fixes the "6 hours from now" issue by ensuring accurate time calculation
 */

class TimeAgoCorrected {
    constructor() {
        this.serverTimezone = 'Asia/Jakarta';
        this.updateInterval = null;
        this.refreshRate = 30000; // 30 seconds
    }

    /**
     * Parse ISO timestamp with timezone consideration
     * @param {string} timestamp - ISO timestamp string
     * @returns {Date} Parsed date object
     */
    parseTimestamp(timestamp) {
        if (!timestamp) return null;
        
        // Handle both ISO and MySQL datetime formats
        const date = new Date(timestamp);
        
        // If invalid date, try parsing as local time
        if (isNaN(date.getTime())) {
            return new Date(timestamp.replace(' ', 'T') + '+07:00');
        }
        
        return date;
    }

    /**
     * Format timestamp to relative time with proper timezone handling
     * @param {string} timestamp - ISO timestamp string
     * @returns {string} Formatted relative time
     */
    format(timestamp) {
        if (!timestamp) return '';
        
        const now = new Date();
        const past = this.parseTimestamp(timestamp);
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
window.timeAgo = new TimeAgoCorrected();
window.timeAgo.init();
