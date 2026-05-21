/* ========================================================
   GLOBAL LOGIC & INTERACTIVITY (Moodify Premium UI)
   ======================================================== */

function toggleMode() {
    var isLight = document.documentElement.classList.toggle('light');

    // Update all theme icons in the page (in case there are multiple)
    const icons = document.querySelectorAll('#themeIcon');
    icons.forEach(el => {
        el.textContent = isLight ? '🌙' : '☀';
    });

    localStorage.setItem(
        'moodify-theme',
        isLight ? 'light' : 'dark'
    );

    // Dispatch custom event for Chart.js and other dynamic visual modules
    document.dispatchEvent(new CustomEvent('themechanged', { 
        detail: { isLight: isLight } 
    }));
}

// Instant theme initialization to prevent flash of wrong theme
(function () {
    if (localStorage.getItem('moodify-theme') === 'light') {
        document.documentElement.classList.add('light');
        
        document.addEventListener('DOMContentLoaded', function () {
            const icons = document.querySelectorAll('#themeIcon');
            icons.forEach(el => {
                el.textContent = '🌙';
            });
        });
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    /* CARD CURSOR GLOW EFFECT */
    document.querySelectorAll('.mood-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty('--x', `${x}px`);
            card.style.setProperty('--y', `${y}px`);
        });
    });

    /* NAVBAR SHADOW ON SCROLL */
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.15)';
                if (document.documentElement.classList.contains('light')) {
                    navbar.style.boxShadow = '0 10px 30px rgba(124, 92, 191, 0.08)';
                }
            } else {
                navbar.style.boxShadow = 'none';
            }
        });
    }
});