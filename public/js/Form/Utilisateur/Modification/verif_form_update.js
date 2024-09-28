document.addEventListener('DOMContentLoaded', function() {
  // Récupération des éléments
  const codeInput = document.querySelector('input[name="modification_utilisateur[code]"]');
  const emailInput = document.querySelector('input[name="modification_utilisateur[email]"]');
  const phoneInput = document.querySelector('input[name="modification_utilisateur[numTelephone]"]');
  const passwordInput = document.querySelector('input[name="modification_utilisateur[newPassword]"]');
  const submitButton = document.querySelector('#submit-modif-button');

  const formCode = document.querySelector('#modification-form-code');
  const formEmail = document.querySelector('#modification-form-email');
  const formPhone = document.querySelector('#modification-form-telephone');
  const formPassword = document.querySelector('#modification-form-password');

  let errorMessageElementCode = null;
  let errorMessageElementEmail = null;
  let errorMessageElementPhone = null;
  let errorMessageElementPassword = null;

  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\W]{8,30}$/; // Regex pour le mot de passe

  function checkFormValidity() {
    const codeValid = !errorMessageElementCode;
    const emailValid = !errorMessageElementEmail;
    const phoneValid = !errorMessageElementPhone;
    const passwordValid = !errorMessageElementPassword;

    // Désactive le bouton si l'un des champs est invalide
    submitButton.disabled = !(codeValid && emailValid && phoneValid && passwordValid);
  }

  // Vérification du code
  codeInput.addEventListener('input', function() {
    const code = codeInput.value;

    if (code.length === 0) {
      if (errorMessageElementCode) {
        formCode.removeChild(errorMessageElementCode);
        errorMessageElementCode = null;
      }
      checkFormValidity();
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
        }
        checkFormValidity(); // Vérifie l'état global après la vérification
      })
      .catch(error => {
        console.error('Erreur lors de la vérification du code:', error);
      });
  });

  // Vérification de l'email
  emailInput.addEventListener('input', function() {
    const email = emailInput.value;

    if (email.length === 0) {
      if (errorMessageElementEmail) {
        formEmail.removeChild(errorMessageElementEmail);
        errorMessageElementEmail = null;
      }
      checkFormValidity();
      return;
    }

    fetch(Routing.generate('verifEditionEmailUser', { email: email }), { method: 'GET' })
      .then(response => {
        if (response.status === 204) {
          if (errorMessageElementEmail) {
            formEmail.removeChild(errorMessageElementEmail);
            errorMessageElementEmail = null;
          }
          return null;
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
        }
        checkFormValidity(); // Vérifie l'état global après la vérification
      })
      .catch(error => {
        console.error('Erreur lors de la vérification de l\'email:', error);
      });
  });

  // Vérification du numéro de téléphone
  const phoneRegex = /^(\+33|0)[1-9](\d{2}){4}$/; // Regex pour numéro de téléphone français

  phoneInput.addEventListener('input', function() {
    const phone = phoneInput.value;

    if (phone.length === 0) {
      if (errorMessageElementPhone) {
        formPhone.removeChild(errorMessageElementPhone);
        errorMessageElementPhone = null;
      }
      checkFormValidity();
      return;
    }

    if (phoneRegex.test(phone)) {
      if (errorMessageElementPhone) {
        formPhone.removeChild(errorMessageElementPhone);
        errorMessageElementPhone = null;
      }
    } else {
      if (!errorMessageElementPhone) {
        errorMessageElementPhone = document.createElement('span');
        errorMessageElementPhone.classList.add('text-red-500', 'text-sm');
        errorMessageElementPhone.textContent = 'Numéro de téléphone invalide';
        formPhone.appendChild(errorMessageElementPhone);
      }
    }

    checkFormValidity(); // Vérifie l'état global après la vérification
  });

  // Vérification du mot de passe
  passwordInput.addEventListener('input', function() {
    const password = passwordInput.value;

    if (password.length === 0) {
      if (errorMessageElementPassword) {
        formPassword.removeChild(errorMessageElementPassword);
        errorMessageElementPassword = null;
      }
      checkFormValidity();
      return;
    }

    if (passwordRegex.test(password)) {
      if (errorMessageElementPassword) {
        formPassword.removeChild(errorMessageElementPassword);
        errorMessageElementPassword = null;
      }
    } else {
      if (!errorMessageElementPassword) {
        errorMessageElementPassword = document.createElement('span');
        errorMessageElementPassword.classList.add('text-red-500', 'text-sm');
        errorMessageElementPassword.textContent = 'Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre';
        formPassword.appendChild(errorMessageElementPassword);
      }
    }

    checkFormValidity(); // Vérifie l'état global après la vérification
  });

  // Empêche la soumission du formulaire si un champ est invalide
  form.addEventListener('submit', function(event) {
    const codeValid = !errorMessageElementCode;
    const emailValid = !errorMessageElementEmail;
    const phoneValid = !errorMessageElementPhone;
    const passwordValid = !errorMessageElementPassword;

    // Si un des champs est invalide, empêche la soumission
    if (!codeValid || !emailValid || !phoneValid || !passwordValid) {
      event.preventDefault();
      alert("Veuillez corriger les erreurs dans le formulaire avant de soumettre.");
    }
  });
});
