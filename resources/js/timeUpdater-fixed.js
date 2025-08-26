/**
 * Enhanced Time Updater - Real-time time display for activities
 * Fixes the time display issue with more accurate real-time updates
 */

class TimeUpdater {
    constructor() {
        this.updateInterval = null;
        this.elements = [];
        this.init();
    }

    init() {
        this.startUpdating();

        // Update every 5 seconds for more accurate real-time feel
        this.updateInterval = setInterval(() => {
            this.updateAllTimes();
        }, 5000);
    }

    findTimeElements() {
        // Find all elements with data-timestamp attribute
        this.elements = document.querySelectorAll("[data-timestamp]");
    }

    updateAllTimes() {
        this.elements.forEach((element) => {
            this.updateSingleTime(element);
        });
    }

    updateSingleTime(element) {
        const timestamp = element.getAttribute("data-timestamp");
        const format = element.getAttribute("data-format") || "relative";

        if (!timestamp) return;

        const activityTime = new Date(timestamp);
        const now = new Date();

        if (format === "relative") {
            element.textContent = this.getRelativeTime(activityTime, now);
        } else if (format === "exact") {
            element.textContent = this.getExactTime(activityTime);
        }
    }

    getRelativeTime(activityTime, now) {
        const diffInSeconds = Math.floor((now - activityTime) / 1000);

        if (diffInSeconds < 5) {
            return "baru saja";
        } else if (diffInSeconds < 60) {
            return `${diffInSeconds} detik yang lalu`;
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} menit yang lalu`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} jam yang lalu`;
        } else if (diffInSeconds < 2592000) {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} hari yang lalu`;
        } else {
            const months = Math.floor(diffInSeconds / 2592000);
            return `${months} bulan yang lalu`;
        }
    }

    getExactTime(activityTime) {
        return activityTime.toLocaleString("id-ID", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
        });
    }

    // Method untuk menambahkan elemen baru secara dinamis
    addElement(element) {
        if (element.hasAttribute("data-timestamp")) {
            this.updateSingleTime(element);
            this.findTimeElements();
        }
    }

    // Method untuk menghentikan update
    stopUpdating() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    }

    // Method untuk memulai update
    startUpdating() {
        this.findTimeElements();
        this.updateAllTimes();
    }

    // Method untuk restart update
    restartUpdating() {
        this.stopUpdating();
        this.startUpdating();
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    window.timeUpdater = new TimeUpdater();
    window.timeUpdater.init();
});

// Export for use in other modules
export { TimeUpdater };
