document.addEventListener('DOMContentLoaded', function() {
  // Vérification du code
  const codeInput = document.querySelector('input[name="modification_utilisateur[code]"]');
  const submitButton = document.querySelector('#submit-modif-button');
  const formCode = document.querySelector('#modification-form-code');
  let errorMessageElementCode = null;

  codeInput.addEventListener('input', function() {
    const code = codeInput.value;

    if (code.length === 0) {
      if (errorMessageElementCode) {
        formCode.removeChild(errorMessageElementCode);
        errorMessageElementCode = null;
      }
      submitButton.disabled = false;
      return;
    }

    fetch(Routing.generate('verifEditionCodeUser', { code: code }), { method: 'GET' })
      .then(response => {
        if (response.status === 400) {
          return response.json();
        } else if (response.status === 204) {
          if (errorMessageElementCode) {
            formCode.removeChild(errorMessageElementCode);
            errorMessageElementCode = null;
          }
          submitButton.disabled = false;
          return null;
        }
      })
      .then(data => {
        if (data && data.error) {
          if (!errorMessageElementCode) {
            errorMessageElementCode = document.createElement('span');
            errorMessageElementCode.classList.add('text-red-500', 'text-sm');
            errorMessageElementCode.textContent = data.error;
            formCode.appendChild(errorMessageElementCode);
          }
          submitButton.disabled = true;
        }
      })
      .catch(error => {
        console.error('Erreur lors de la vérification du code:', error);
      });
  });

  // Vérification de l'email
  const emailInput = document.querySelector('input[name="modification_utilisateur[email]"]');
  const formEmail = document.querySelector('#modification-form-email');
  let errorMessageElementEmail = null;

  emailInput.addEventListener('input', function() {
    const email = emailInput.value;

    if (email.length === 0) {
      if (errorMessageElementEmail) {
        formEmail.removeChild(errorMessageElementEmail);
        errorMessageElementEmail = null;
      }
      submitButton.disabled = false;
      return;
    }

    fetch(Routing.generate('verifEditionEmailUser', { email: email }), { method: 'GET' })
      .then(response => {
        if (response.status === 204) {
          if (errorMessageElementEmail) {
            formEmail.removeChild(errorMessageElementEmail);
            errorMessageElementEmail = null;
          }
          submitButton.disabled = false;
        } else {
          return response.json();
        }
      })
      .then(data => {
        if (data && data.error) {
          if (!errorMessageElementEmail) {
            errorMessageElementEmail = document.createElement('span');
            errorMessageElementEmail.classList.add('text-red-500', 'text-sm');
            errorMessageElementEmail.textContent = data.error;
            formEmail.appendChild(errorMessageElementEmail);
          }
          submitButton.disabled = true;
        }
      })
      .catch(error => {
        console.error('Erreur lors de la vérification de l\'email:', error);
      });
  });

  // Vérification du numéro de téléphone
  const phoneInput = document.querySelector('input[name="modification_utilisateur[telephone]"]');
  const formPhone = document.querySelector('#modification-form-telephone');
  let errorMessageElementPhone = null;

  const phoneRegex = /^(\+33|0)[1-9](\d{2}){4}$/; // Regex pour numéro de téléphone français

  phoneInput.addEventListener('input', function() {
    const phone = phoneInput.value;

    if (phone.length === 0 || phoneRegex.test(phone)) {
      if (errorMessageElementPhone) {
        formPhone.removeChild(errorMessageElementPhone);
        errorMessageElementPhone = null;
      }
      submitButton.disabled = false;
      return;
    } else {
      if (!errorMessageElementPhone) {
        errorMessageElementPhone = document.createElement('span');
        errorMessageElementPhone.classList.add('text-red-500', 'text-sm');
        errorMessageElementPhone.textContent = 'Numéro de téléphone invalide';
        formPhone.appendChild(errorMessageElementPhone);
      }
      submitButton.disabled = true;
    }
  });

  // Empêche la soumission du formulaire si un champ est invalide
  form.addEventListener('submit', function(event) {
    if (submitButton.disabled) {
      event.preventDefault();
    }
  });
});
