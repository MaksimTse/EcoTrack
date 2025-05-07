document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger-toggle');
    const mobileNav = document.getElementById('mobile-nav');

    burger.addEventListener('click', () => {
        mobileNav.classList.toggle('show');
        burger.textContent = mobileNav.classList.contains('show') ? '✖' : '☰';
    });

    document.querySelectorAll('.mobile-nav a').forEach(link => {
        link.addEventListener('click', () => {
            mobileNav.classList.remove('show');
            burger.textContent = '☰';
        });
    });
});
