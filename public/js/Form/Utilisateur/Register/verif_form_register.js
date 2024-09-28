document.addEventListener('DOMContentLoaded', function() {
  const codeInput = document.querySelector('input[name="registration_form[code]"]');
  const emailInput = document.querySelector('input[name="registration_form[email]"]');
  const loginInput = document.querySelector('input[name="registration_form[login]"]');
  const passwordInput = document.querySelector('input[name="registration_form[plainPassword]"]');
  const submitButton = document.querySelector('#submit-button');

  const formCode = document.querySelector('#form-code');
  const formEmail = document.querySelector('#form-email');
  const formLogin = document.querySelector('#form-login');
  const formPassword = document.querySelector('#form-password');

  let errorMessageElements = {
    code: null,
    email: null,
    login: null,
    password: null
  };

  function checkFormValidity() {
    return !submitButton.disabled;
  }

  function validateInput(field, value, route, formElement, errorKey) {
    fetch(Routing.generate(route, { [field]: value }), { method: 'GET' })
      .then(response => {
        if (response.status === 204) {
          if (errorMessageElements[errorKey]) {
            formElement.removeChild(errorMessageElements[errorKey]);
            errorMessageElements[errorKey] = null;
          }
        } else {
          return response.json();
        }
      })
      .then(data => {
        if (data && data.error) {
          if (!errorMessageElements[errorKey]) {
            errorMessageElements[errorKey] = document.createElement('span');
            errorMessageElements[errorKey].classList.add('text-red-500', 'text-sm');
            errorMessageElements[errorKey].textContent = data.error;
            formElement.appendChild(errorMessageElements[errorKey]);
          }
        }
        updateSubmitButtonState();
      })
      .catch(error => {
        console.error(`Erreur lors de la vérification du ${field}:`, error);
      });
  }

  function updateSubmitButtonState() {
    const isCodeValid = !errorMessageElements.code;
    const isEmailValid = !errorMessageElements.email;
    const isLoginValid = !errorMessageElements.login;
    const isPasswordValid = !errorMessageElements.password;

    submitButton.disabled = !(isCodeValid && isEmailValid && isLoginValid && isPasswordValid);
  }

  codeInput.addEventListener('input', function() {
    validateInput('code', codeInput.value, 'verifCreationCodeUser', formCode, 'code');
  });

  emailInput.addEventListener('input', function() {
    validateInput('email', emailInput.value, 'verifCreationEmailUser', formEmail, 'email');
  });

  loginInput.addEventListener('input', function() {
    validateInput('login', loginInput.value, 'verifLoginUser', formLogin, 'login');
  });

  passwordInput.addEventListener('input', function() {
    validateInput('password', passwordInput.value, 'verifCreationPasswordUser', formPassword, 'password');
  });

  form.addEventListener('submit', function(event) {
    if (!checkFormValidity()) {
      event.preventDefault();
    }
  });
});
