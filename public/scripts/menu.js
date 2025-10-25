document.addEventListener('DOMContentLoaded', () => {
  const burger = document.getElementById('burgerBtn');
  const nav    = document.querySelector('.user-area');
  if (!burger || !nav) return;

  const mq = window.matchMedia('(max-width: 768px)');

  const closeMenu = () => {
    nav.classList.remove('open');
    burger.classList.remove('active');
    burger.setAttribute('aria-expanded', 'false');
  };

  const toggleMenu = () => {
    if (!mq.matches) return; // Pas de menu burger en desktop
    const willOpen = !nav.classList.contains('open');
    nav.classList.toggle('open', willOpen);
    burger.classList.toggle('active', willOpen);
    burger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
  };

  // Ouvrir / fermer au clic sur le burger
  burger.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleMenu();
  });

  // Fermer si on clique en dehors
  document.addEventListener('click', (e) => {
    if (!mq.matches) return;
    if (!nav.contains(e.target) && e.target !== burger) {
      closeMenu();
    }
  });

  // Fermer si on clique un lien de la nav
  nav.addEventListener('click', (e) => {
    if (e.target.closest('a')) closeMenu();
  });

  // Fermer avec Échap
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
  });

  // En repassant en desktop, s'assurer que le menu est fermé
  window.addEventListener('resize', () => {
    if (!mq.matches) closeMenu();
  });
});
