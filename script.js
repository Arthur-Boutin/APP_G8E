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


const faqQuestions = document.querySelectorAll('.faq-question, .legal-question');

faqQuestions.forEach(question => {
    question.addEventListener('click', () => {
        const faqItem = question.parentElement;
        const answer = faqItem.querySelector('.faq-answer, .legal-answer');
        const toggleIcon = question.querySelector('.right-icon');

        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            toggleIcon.src = './assets/images/icons8-chevron-down-50.png';
        } else {
            answer.style.display = 'block';
            toggleIcon.src = './assets/images/icons8-chevron-up-50.png';
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    loadFaqs();

    document.getElementById('add-faq-btn').addEventListener('click', () => {
        openForm();
    });

    document.getElementById('cancel-faq-btn').addEventListener('click', () => {
        closeForm();
    });

    document.getElementById('save-faq-btn').addEventListener('click', () => {
        saveFaq();
    });
});

function loadFaqs() {
    fetch('faq_api.php?action=list')
        .then(response => response.json())
        .then(faqs => {
            const tbody = document.getElementById('faq-list');
            tbody.innerHTML = '';
            faqs.forEach(faq => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
          <td>${faq.id}</td>
          <td>${faq.question}</td>
          <td>
            <button onclick="editFaq(${faq.id})">Modifier</button>
            <button onclick="deleteFaq(${faq.id})">Supprimer</button>
          </td>
        `;
                tbody.appendChild(tr);
            });
        });
}

function openForm(faq = null) {
    document.getElementById('faq-form').style.display = 'block';
    if (faq) {
        document.getElementById('form-title').textContent = 'Modifier une FAQ';
        document.getElementById('faq-id').value = faq.id;
        document.getElementById('faq-question').value = faq.question;
        document.getElementById('faq-answer').value = faq.answer;
    } else {
        document.getElementById('form-title').textContent = 'Ajouter une FAQ';
        document.getElementById('faq-id').value = '';
        document.getElementById('faq-question').value = '';
        document.getElementById('faq-answer').value = '';
    }
}

function closeForm() {
    document.getElementById('faq-form').style.display = 'none';
}

function saveFaq() {
    const id = document.getElementById('faq-id').value;
    const question = document.getElementById('faq-question').value;
    const answer = document.getElementById('faq-answer').value;

    const data = new FormData();
    data.append('question', question);
    data.append('answer', answer);

    let url = 'faq_api.php?action=add';
    if (id) {
        url = 'faq_api.php?action=update&id=' + id;
    }

    fetch(url, {
        method: 'POST',
        body: data
    }).then(() => {
        closeForm();
        loadFaqs();
    });
}

function editFaq(id) {
    fetch('faq_api.php?action=get&id=' + id)
        .then(response => response.json())
        .then(faq => {
            openForm(faq);
        });
}

function deleteFaq(id) {
    if (confirm('Voulez-vous supprimer cette FAQ ?')) {
        fetch('faq_api.php?action=delete&id=' + id)
            .then(() => {
                loadFaqs();
            });
    }
}
