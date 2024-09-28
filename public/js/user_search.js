const searchInput = document.getElementById('user-search');
const userList = document.getElementById('user-list');

searchInput.addEventListener('input', (event) => {
  const users = Array.from(userList.children);
  const typedLogin = searchInput.value.toLowerCase();
  for (let uid in users) {
    const user = users[uid];
    const login = user.dataset.userlogin.toLowerCase();
    if (login.includes(typedLogin)) {
      user.style.display = '';
    } else {
      user.style.display = 'none';
    }
  }
});
