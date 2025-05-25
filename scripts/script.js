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


const faqQuestions = document.querySelectorAll('.faq-question, .legal-question,.FAQ-question');

faqQuestions.forEach(question => {
    question.addEventListener('click', () => {
        const faqItem = question.parentElement;
        const answer = faqItem.querySelector('.faq-answer, .legal-answer,.FAQ-answer');
        const toggleIcon = question.querySelector('.right-icon');

        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            toggleIcon.src = '../assets/images/icons8-chevron-down-50.png';
        } else {
            answer.style.display = 'block';
            toggleIcon.src = '../assets/images/icons8-chevron-up-50.png';
        }
    });
});

