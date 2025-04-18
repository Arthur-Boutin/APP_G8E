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