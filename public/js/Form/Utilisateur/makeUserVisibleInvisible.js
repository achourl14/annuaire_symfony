async function makeUserVisibleInvisible(id) {
  let buttonVisible = document.getElementById('buttonVisible');

  let routing = Routing.generate('app_visible', { id: id });

  try {
    let response = await fetch(routing, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      }
    });

    if (response.ok) {
      let data = await response.json();

      // Vérifie si l'utilisateur est visible ou non
      if (data.visible) {
        // Si l'utilisateur est maintenant visible, on met à jour le bouton en vert
        buttonVisible.className = "text-green-500 hover:text-white border border-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-400 dark:text-green-400 dark:hover:text-white dark:hover:bg-green-500 dark:focus:ring-green-700";
        buttonVisible.textContent = 'Visible';
      } else {
        // Si l'utilisateur est maintenant invisible, on met à jour le bouton en rouge
        buttonVisible.className = "text-red-500 hover:text-white border border-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-400 dark:text-red-400 dark:hover:text-white dark:hover:bg-red-500 dark:focus:ring-red-700";
        buttonVisible.textContent = 'Invisible';
      }
    } else {
      console.error('Erreur lors de la mise à jour de la visibilité.');
    }
  } catch (error) {
    console.error('Erreur réseau:', error);
  }
}
