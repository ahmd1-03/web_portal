/**
 * Activity Monitor untuk mendeteksi aktivitas user dan mengirim heartbeat
 * ke server untuk memperbarui last_activity
 */

class ActivityMonitor {
    constructor() {
        this.inactivityTimeout = null;
        this.heartbeatInterval = null;
        this.inactivityDuration = 55 * 60 * 1000; // 55 menit (5 menit sebelum timeout)
        this.heartbeatIntervalTime = 5 * 60 * 1000; // 5 menit
        
        this.init();
    }

    init() {
        // Deteksi berbagai jenis aktivitas
        this.setupActivityListeners();
        
        // Mulai heartbeat untuk memperbarui last_activity
        this.startHeartbeat();
        
        // Setup inactivity timer
        this.resetInactivityTimer();
    }

    setupActivityListeners() {
        const events = [
            'mousemove', 'mousedown', 'keypress', 'scroll', 
            'touchstart', 'click', 'input', 'change'
        ];

        events.forEach(event => {
            document.addEventListener(event, () => {
                this.resetInactivityTimer();
            });
        });
    }

    resetInactivityTimer() {
        // Clear existing timeout
        if (this.inactivityTimeout) {
            clearTimeout(this.inactivityTimeout);
        }

        // Set new timeout
        this.inactivityTimeout = setTimeout(() => {
            this.handleInactivity();
        }, this.inactivityDuration);
    }

    handleInactivity() {
        console.log('User tidak aktif, akan logout otomatis dalam 5 menit');
        // Bisa tambahkan notifikasi ke user di sini
    }

    startHeartbeat() {
        // Kirim heartbeat pertama kali
        this.sendHeartbeat();

        // Set interval untuk heartbeat
        this.heartbeatInterval = setInterval(() => {
            this.sendHeartbeat();
        }, this.heartbeatIntervalTime);
    }

    async sendHeartbeat() {
        try {
            const response = await fetch('/admin/heartbeat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                console.warn('Heartbeat gagal:', response.status);
            }
        } catch (error) {
            console.warn('Error mengirim heartbeat:', error);
        }
    }

    destroy() {
        if (this.inactivityTimeout) {
            clearTimeout(this.inactivityTimeout);
        }
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
        }
        
        // Remove event listeners
        const events = [
            'mousemove', 'mousedown', 'keypress', 'scroll', 
            'touchstart', 'click', 'input', 'change'
        ];
        
        events.forEach(event => {
            document.removeEventListener(event, this.resetInactivityTimer);
        });
    }
}

// Export untuk penggunaan global
window.ActivityMonitor = ActivityMonitor;

// Auto-initialize jika di halaman admin
if (document.body.classList.contains('admin-page')) {
    document.addEventListener('DOMContentLoaded', () => {
        window.activityMonitor = new ActivityMonitor();
    });
}
