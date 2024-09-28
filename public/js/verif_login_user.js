document.addEventListener('DOMContentLoaded', function() {
  const loginInput = document.querySelector('input[name="registration_form[login]"]');
  const submitButton = document.querySelector('#submit-button');
  const formLogin = document.querySelector('#form-login');
  let errorMessageElement = null;

  loginInput.addEventListener('input', function() {
    const login = loginInput.value;

    if (login.length === 0) {
      if (errorMessageElement) {
        formLogin.removeChild(errorMessageElement);
        errorMessageElement = null;
      }
      submitButton.disabled = false;
      return;
    }

    fetch(Routing.generate('verifLoginUser', { login: login }), { method: 'GET' })
      .then(response => {
        if (response.status === 204) {
          if (errorMessageElement) {
            formLogin.removeChild(errorMessageElement);
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
            formLogin.appendChild(errorMessageElement);
          }
          submitButton.disabled = true;
        }
      })
      .catch(error => {
        console.error('Erreur lors de la vérification du login:', error);
      });
  });
});
