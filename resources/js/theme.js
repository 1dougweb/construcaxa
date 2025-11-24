// Theme Management
(function () {
    'use strict';

    // Get theme from localStorage or default to light mode
    const getTheme = () => {
        const stored = localStorage.getItem('darkMode');
        if (stored !== null) {
            return stored === 'true';
        }
        // Check system preference
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    };

    // Apply theme to document
    const applyTheme = (isDark) => {
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('darkMode', isDark);
    };

    // Initialize theme on page load
    const initTheme = () => {
        const isDark = getTheme();
        applyTheme(isDark);
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTheme);
    } else {
        initTheme();
    }

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        // Only auto-switch if user hasn't manually set a preference
        if (localStorage.getItem('darkMode') === null) {
            applyTheme(e.matches);
        }
    });
})();
