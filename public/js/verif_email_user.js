document.addEventListener('DOMContentLoaded', function() {
  const emailInput = document.querySelector('input[name="registration_form[email]"]');
  const submitButton = document.querySelector('#submit-button');
  const formEmail = document.querySelector('#form-email');
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

    fetch(Routing.generate('verifEmailUser', { email: email }), { method: 'GET' })
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
