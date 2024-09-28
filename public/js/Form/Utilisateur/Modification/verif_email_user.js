document.addEventListener('DOMContentLoaded', function() {
  const emailInput = document.querySelector('input[name="modification_utilisateur[email]"]'); // Correspond à l'input de l'email
  const submitButton = document.querySelector('#modification-submit-button'); // Le bouton de soumission pour le formulaire de modification
  const formEmail = document.querySelector('#modification-form-email'); // Conteneur de l'input de l'email
  let errorMessageElement = null;

  emailInput.addEventListener('input', function() {
    const email = emailInput.value;

    if (email.length === 0) {
      if (errorMessageElement) {
        formEmail.removeChild(errorMessageElement);
        errorMessageElement = null;
      }
      submitButton.disabled = false;
      return;
    }

    fetch(Routing.generate('verifEditionEmailUser', { email: email }), { method: 'GET' })
      .then(response => {
        if (response.status === 204) {
          if (errorMessageElement) {
            formEmail.removeChild(errorMessageElement);
            errorMessageElement = null;
          }
          submitButton.disabled = false;
        } else {
          return response.json();
        }
      })
      .then(data => {
        if (data && data.error) {
          if (!errorMessageElement) {
            errorMessageElement = document.createElement('span');
            errorMessageElement.classList.add('text-red-500', 'text-sm');
            errorMessageElement.textContent = data.error;
            formEmail.appendChild(errorMessageElement);
          }
          submitButton.disabled = true;
        }
      })
      .catch(error => {
        console.error('Erreur lors de la vérification de l\'email:', error);
      });
  });
});
