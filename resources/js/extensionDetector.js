/**
 * Browser Extension Detector
 * Helps identify extensions that might be causing console warnings
 */

class ExtensionDetector {
    constructor() {
        this.detectedExtensions = [];
        this.knownProblematicExtensions = {
            'cnlhokffphohmfcddnibpohmkdfafdli': {
                name: 'Unknown Extension',
                description: 'Extension causing postMessage warnings',
                status: 'problematic'
            }
        };
    }

    /**
     * Detect installed Chrome extensions
     */
    async detectExtensions() {
        const extensions = [];

        try {
            // Check for common extension patterns
            const extensionIds = Object.keys(this.knownProblematicExtensions);

            for (const extId of extensionIds) {
                const isInstalled = await this.checkExtensionInstalled(extId);
                if (isInstalled) {
                    extensions.push({
                        id: extId,
                        ...this.knownProblematicExtensions[extId],
                        detected: true
                    });
                }
            }

            // Check for other extension indicators
            if (window.chrome && window.chrome.runtime) {
                extensions.push({
                    id: 'chrome-runtime',
                    name: 'Chrome Runtime API',
                    description: 'Chrome extension runtime detected',
                    status: 'neutral'
                });
            }

        } catch (error) {
            console.log('Extension detection completed with some limitations');
        }

        this.detectedExtensions = extensions;
        return extensions;
    }

    /**
     * Check if a specific extension is installed
     */
    async checkExtensionInstalled(extensionId) {
        return new Promise((resolve) => {
            try {
                // Try to access extension resources
                const img = new Image();
                img.onload = () => resolve(true);
                img.onerror = () => resolve(false);
                img.src = `chrome-extension://${extensionId}/manifest.json`;

                // Timeout after 1 second
                setTimeout(() => resolve(false), 1000);
            } catch (error) {
                resolve(false);
            }
        });
    }

    /**
     * Get list of problematic extensions
     */
    getProblematicExtensions() {
        return this.detectedExtensions.filter(ext => ext.status === 'problematic');
    }

    /**
     * Log extension information to console
     */
    logExtensionInfo() {
        if (this.detectedExtensions.length > 0) {
            console.group('🔍 Detected Browser Extensions:');
            this.detectedExtensions.forEach(ext => {
                const status = ext.status === 'problematic' ? '⚠️' : 'ℹ️';
                console.log(`${status} ${ext.name} (${ext.id}): ${ext.description}`);
            });
            console.groupEnd();

            const problematic = this.getProblematicExtensions();
            if (problematic.length > 0) {
                console.warn('🚨 Problematic extensions detected. Consider disabling them for cleaner console output.');
            }
        } else {
            console.log('✅ No problematic extensions detected');
        }
    }

    /**
     * Generate recommendations for cleaner console
     */
    getRecommendations() {
        const recommendations = [];
        const problematic = this.getProblematicExtensions();

        if (problematic.length > 0) {
            recommendations.push('Consider disabling the following extensions for cleaner console:');
            problematic.forEach(ext => {
                recommendations.push(`- ${ext.name} (${ext.id})`);
            });
        }

        recommendations.push('Console warnings from extensions are now filtered out by ConsoleCleaner');

        return recommendations;
    }
}

// Auto-initialize and expose globally
if (typeof window !== 'undefined') {
    window.extensionDetector = new ExtensionDetector();

    // Run detection after page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.extensionDetector.detectExtensions().then(() => {
                    if (process.env.NODE_ENV === 'development') {
                        window.extensionDetector.logExtensionInfo();
                    }
                });
            }, 1000);
        });
    }
}

export default ExtensionDetector;
