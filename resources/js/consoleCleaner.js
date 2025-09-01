/**
 * Console Cleaner - Suppresses external script warnings to keep console clean
 * This script filters out common warnings from browser extensions and third-party scripts
 */

class ConsoleCleaner {
    constructor() {
        this.originalConsoleWarn = console.warn;
        this.originalConsoleError = console.error;
        this.filteredWarnings = [
            // Chrome extension postMessage warnings
            /Failed to execute 'postMessage' on 'DOMWindow'/,
            /The target origin provided.*does not match the recipient window's origin/,

            // iframe sandbox warnings
            /An iframe which has both allow-scripts and allow-same-origin/,
            /can escape its sandboxing/,

            // reCAPTCHA warnings
            /Unrecognized feature: 'private-token'/,
            /recaptcha.*js/,

            // Extension related warnings
            /injectContent\.js/,
            /simulator\.js/,
            /chrome-extension:\/\//
        ];

        this.init();
    }

    init() {
        // Override console.warn to filter out unwanted warnings
        console.warn = (...args) => {
            const message = args.join(' ');

            // Check if the warning should be filtered
            const shouldFilter = this.filteredWarnings.some(pattern => {
                return pattern.test(message);
            });

            if (!shouldFilter) {
                this.originalConsoleWarn.apply(console, args);
            }
        };

        // Override console.error for similar filtering (less aggressive)
        console.error = (...args) => {
            const message = args.join(' ');

            // Only filter specific extension-related errors
            const shouldFilter = /chrome-extension:\/\//.test(message) ||
                                /injectContent\.js/.test(message) ||
                                /simulator\.js/.test(message);

            if (!shouldFilter) {
                this.originalConsoleError.apply(console, args);
            }
        };

        // Log that cleaner is active (only in development)
        if (process.env.NODE_ENV === 'development') {
            console.log('🧹 Console Cleaner active - filtering external script warnings');
        }
    }

    // Method to add custom filters
    addFilter(pattern) {
        if (pattern instanceof RegExp) {
            this.filteredWarnings.push(pattern);
        } else {
            this.filteredWarnings.push(new RegExp(pattern, 'i'));
        }
    }

    // Method to remove filters
    removeFilter(pattern) {
        this.filteredWarnings = this.filteredWarnings.filter(filter => {
            return !(filter.source === pattern || filter === pattern);
        });
    }

    // Method to get current filters
    getFilters() {
        return this.filteredWarnings.map(filter => filter.source);
    }

    // Method to temporarily disable filtering
    disable() {
        console.warn = this.originalConsoleWarn;
        console.error = this.originalConsoleError;
    }

    // Method to re-enable filtering
    enable() {
        this.init();
    }
}

// Auto-initialize when script loads
if (typeof window !== 'undefined') {
    window.consoleCleaner = new ConsoleCleaner();
}

export default ConsoleCleaner;
