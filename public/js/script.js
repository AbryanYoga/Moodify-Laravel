function toggleMode() {

    var isLight =
        document.documentElement
        .classList
        .toggle('light');

    document.getElementById('themeIcon')
        .textContent = isLight ? '🌙' : '☀';

    localStorage.setItem(
        'moodify-theme',
        isLight ? 'light' : 'dark'
    );
}

(function () {

    if (
        localStorage.getItem('moodify-theme')
        === 'light'
    ) {

        document.documentElement
            .classList
            .add('light');

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                var el =
                    document.getElementById('themeIcon');

                if (el) {
                    el.textContent = '🌙';
                }

            }
        );
    }

})();

/* CARD CURSOR GLOW */

document.querySelectorAll('.mood-card')
.forEach(card => {

    card.addEventListener('mousemove', e => {

        const rect =
            card.getBoundingClientRect();

        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        card.style.setProperty('--x', `${x}px`);
        card.style.setProperty('--y', `${y}px`);

    });

});

/* NAVBAR SHADOW ON SCROLL */

window.addEventListener('scroll', () => {

    const navbar =
        document.querySelector('.navbar');

    if(window.scrollY > 20){

        navbar.style.boxShadow =
        '0 10px 30px rgba(0,0,0,0.25)';

    } else {

        navbar.style.boxShadow = 'none';
    }

});