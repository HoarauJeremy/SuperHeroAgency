document.addEventListener('DOMContentLoaded', () => {
    // Bouton pour ouvrir/fermer le menu mobile
    const menuButton = document.querySelector('button[type="button"]');
    const mobileMenu = document.querySelector('[role="dialog"]');
    const closeButton = mobileMenu.querySelector('button[type="button"]');

    // Toggle menu mobile
    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    closeButton.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
    });

    // Sous-menus
    const submenuButtons = document.querySelectorAll('[aria-controls]');
    submenuButtons.forEach((button) => {
        const submenu = document.getElementById(button.getAttribute('aria-controls'));

        button.addEventListener('click', () => {
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', !isExpanded);
            submenu.classList.toggle('hidden');
        });
    });
});
