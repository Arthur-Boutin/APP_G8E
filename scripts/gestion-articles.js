document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', () => {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
            // Logique pour supprimer l'article
            alert('Article supprimé.');
        }
    });
});