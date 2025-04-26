document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.querySelector(".hamburger");
    const mobileMenu = document.querySelector(".mobile-menu");
  
    // Vérifiez si les éléments existent avant d'ajouter des événements
    if (hamburger && mobileMenu) {
      hamburger.addEventListener("click", () => {
        mobileMenu.classList.toggle("active");
        console.log("Menu hamburger cliqué !"); // Debugging
      });
    } else {
      console.error("Éléments hamburger ou mobileMenu introuvables !");
    }
  });


// FAQ toggle
const questionHeaders = document.querySelectorAll('.question-header');

questionHeaders.forEach(header => {
    header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        const rightArrow = header.querySelector('.right-arrow');
        const toggleIcon = header.querySelector('.toggle-icon');

        // Afficher le contenu en mode expansion/réduction
        content.style.display = content.style.display === 'block' ? 'none' : 'block';

        // Rotation de la flèche
        if (content.style.display === 'block') {
            rightArrow.classList.add('rotate');
            toggleIcon.src = "icons/icons8-chevron-up-50.png"; // Élargir vers le haut
        } else {
            rightArrow.classList.remove('rotate');
            toggleIcon.src = "icons/icons8-chevron-down-50.png"; // Fermé en cas de fermeture
        }
    });
});
