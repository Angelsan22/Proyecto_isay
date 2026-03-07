/**
 * MACUIN — theme.js
 * Toggle modo oscuro / claro.
 * Persiste la preferencia en localStorage.
 * Incluir en el <head> (antes del </body>) de cada blade.
 */

(function () {
    const KEY = 'macuin_theme';

    /* ── Aplica el tema guardado lo antes posible (evita flash) ── */
    const saved = localStorage.getItem(KEY);
    if (saved === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
    }

    /* ── Conecta el botón una vez que el DOM esté listo ── */
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('theme-toggle');
        if (!btn) return;

        btn.addEventListener('click', () => {
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';

            if (isLight) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem(KEY, 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem(KEY, 'light');
            }
        });
    });
})();