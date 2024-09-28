document.addEventListener('DOMContentLoaded', function() {
  const codeInput = document.querySelector('input[name="modification_utilisateur[code]"]'); // Correspond à l'input du code dans le formulaire de modification
  const submitButton = document.querySelector('#submit-modif-button'); // Le bouton de soumission pour le formulaire de modification
  const form = document.querySelector('form[name="modification_utilisateur"]'); // Le formulaire de modification
  const formCode = document.querySelector('#modification-form-code'); // Conteneur de l'input du code
  let errorMessageElement = null;

  codeInput.addEventListener('input', function() {
    const code = codeInput.value;

    if (code.length === 0) {
      if (errorMessageElement) {
        formCode.removeChild(errorMessageElement);
        errorMessageElement = null;
      }
      submitButton.disabled = false;
      return;
    }

    fetch(Routing.generate('verifEditionCodeUser', { code: code }), {
      method: 'GET',
    })
      .then(response => {
        if (response.status === 400) {
          return response.json();
        } else if (response.status === 204) {
          if (errorMessageElement) {
            formCode.removeChild(errorMessageElement);
            errorMessageElement = null;
          }
          submitButton.disabled = false;
          return null;
        }
      })
      .then(data => {
        if (data && data.error) {
          if (!errorMessageElement) {
            errorMessageElement = document.createElement('span');
            errorMessageElement.classList.add('text-red-500', 'text-sm');
            errorMessageElement.textContent = data.error;
            formCode.appendChild(errorMessageElement);
          }
          submitButton.disabled = true;
        }
      })
      .catch(error => {
        console.error('Erreur lors de la vérification du code:', error);
      });
  });

  form.addEventListener('submit', function(event) {
    if (submitButton.disabled) {
      event.preventDefault(); // Empêche la soumission du formulaire si le bouton est désactivé
    }
  });
});
