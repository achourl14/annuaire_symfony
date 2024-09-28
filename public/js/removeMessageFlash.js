// Fonction pour faire disparaître automatiquement les messages flash
document.addEventListener('DOMContentLoaded', function() {
  const flashMessages = document.querySelectorAll('#flashes-container > div');

  flashMessages.forEach(message => {
    // Attendre 4 secondes avant de cacher l'élément
    setTimeout(() => {
      message.classList.add('opacity-0', 'transition-opacity', 'duration-1000'); // Ajoute une transition
      setTimeout(() => {
        message.remove(); // Supprime complètement l'élément après la transition
      }, 1000); // Temps pour la transition (1 seconde ici)
    }, 3000); // Délai avant de commencer la transition (4 secondes ici)
  });
});
