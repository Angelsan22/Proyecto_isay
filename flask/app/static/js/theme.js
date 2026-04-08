// Gestor de Temas Global MACUIN
(function() {
    const savedTheme = localStorage.getItem('theme');

    // Aplicar inmediatamente si hay algo guardado (antes de que cargue el body completamente para evitar flash)
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-theme');
    }

    function updateUI(isDark) {
        const themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;
        
        const themeIcon = themeToggle.querySelector('i');
        const themeText = document.getElementById('themeText');
        
        if (isDark) {
            if (themeIcon) themeIcon.className = 'bi bi-sun';
            if (themeText) themeText.innerText = 'Modo Claro';
        } else {
            if (themeIcon) themeIcon.className = 'bi bi-moon-stars';
            if (themeText) themeText.innerText = 'Modo Obscuro';
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        const bodyTag = document.body;
        const themeToggle = document.getElementById('themeToggle');

        // Limpiar el root y pasar la clase al body
        if (document.documentElement.classList.contains('dark-theme')) {
            document.documentElement.classList.remove('dark-theme');
            bodyTag.classList.add('dark-theme');
        } else if (localStorage.getItem('theme') === 'dark') {
            bodyTag.classList.add('dark-theme');
        }

        // Inicializar UI si existe el botón
        updateUI(bodyTag.classList.contains('dark-theme'));

        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const isDark = bodyTag.classList.contains('dark-theme');
                const newIsDark = !isDark;
                
                if (newIsDark) {
                    bodyTag.classList.add('dark-theme');
                } else {
                    bodyTag.classList.remove('dark-theme');
                }
                
                updateUI(newIsDark);
                localStorage.setItem('theme', newIsDark ? 'dark' : 'light');
                
                // Disparar evento personalizado para componentes como Chart.js
                window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newIsDark ? 'dark' : 'light' } }));
            });
        }
    });
})();
