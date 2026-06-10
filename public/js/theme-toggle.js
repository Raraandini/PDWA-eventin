// Check local storage or system preference to apply dark mode
function initTheme() {
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

// Toggle theme function
function toggleTheme() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
    updateThemeIcon();
}

// Update the icon based on current theme
function updateThemeIcon() {
    const iconLight = document.getElementById('theme-icon-light');
    const iconDark = document.getElementById('theme-icon-dark');
    
    if (iconLight && iconDark) {
        if (document.documentElement.classList.contains('dark')) {
            iconLight.classList.add('hidden');
            iconDark.classList.remove('hidden');
        } else {
            iconLight.classList.remove('hidden');
            iconDark.classList.add('hidden');
        }
    }
}

// Initialize on script load
initTheme();

// Wait for DOM to attach listeners
document.addEventListener('DOMContentLoaded', () => {
    updateThemeIcon();
    
    const themeToggles = document.querySelectorAll('.theme-toggle-btn');
    themeToggles.forEach(btn => {
        btn.addEventListener('click', toggleTheme);
    });
});
