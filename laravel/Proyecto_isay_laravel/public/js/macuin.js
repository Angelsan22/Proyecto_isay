/**
 * MACUIN — macuin.js
 * Toggle modo oscuro / claro con persistencia en localStorage.
 * El bloque IIFE en el <head> evita el flash al recargar la página.
 */

/* ── Aplica el tema ANTES de que el navegador pinte (sin flash) ──
   Pega este bloque inline en el <head> de cada blade, ANTES del </head>:

   <script>
     (function(){
       if(localStorage.getItem('macuin_theme')==='light')
         document.documentElement.setAttribute('data-theme','light');
     })();
   </script>

   El resto del script puede estar en el <body> o cargarse con defer.
──────────────────────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;

    btn.addEventListener('click', () => {
        const isLight = document.documentElement.getAttribute('data-theme') === 'light';

        if (isLight) {
            document.documentElement.removeAttribute('data-theme');
            localStorage.setItem('macuin_theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('macuin_theme', 'light');
        }
    });
});