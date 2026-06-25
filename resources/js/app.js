function initTheme() {
    const stored = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = stored || (prefersDark ? 'dark' : 'light');
    document.documentElement.classList.toggle('dark', theme === 'dark');
    return theme;
}

function toggleTheme() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateToggleIcon(isDark);
}

function updateToggleIcon(isDark) {
    document.querySelectorAll('.theme-toggle').forEach(btn => {
        const icon = btn.querySelector('.material-symbols-outlined');
        if (icon) icon.textContent = isDark ? 'light_mode' : 'dark_mode';
    });
}

window.initTheme = initTheme;
window.toggleTheme = toggleTheme;

initTheme();
updateToggleIcon(document.documentElement.classList.contains('dark'));

// Livewire wire:navigate doesn't fire DOMContentLoaded, so update on navigate
document.addEventListener('livewire:navigated', () => {
    updateToggleIcon(document.documentElement.classList.contains('dark'));
});
