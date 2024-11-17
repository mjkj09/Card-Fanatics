document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenu = document.getElementById('close-menu');
    const fullscreenMenu = document.getElementById('fullscreen-menu');

    // Otwórz menu
    menuToggle.addEventListener('click', function() {
        fullscreenMenu.style.display = 'flex';
    });

    // Zamknij menu
    closeMenu.addEventListener('click', function() {
        fullscreenMenu.style.display = 'none';
    });

    // Ukryj menu przy kliknięciu opcji
    document.querySelectorAll('.menu-options li').forEach(item => {
        item.addEventListener('click', function() {
            fullscreenMenu.style.display = 'none';
        });
    });
});
