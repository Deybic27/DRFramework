document.addEventListener('DOMContentLoaded', (e) => {

  var btn = document.querySelector('#user-create #send')
  console.log(btn);
  btn.addEventListener('click', function(e) {
    e.preventDefault
    userCreate()
  })


})
async function userCreate() {
  const form = document.querySelector('#user-create');
  var name = form.querySelector('#name').value
  var email = form.querySelector('#email').value
  var password = form.querySelector('#password').value

  await postData(document.location.origin + '/api/user.php', { name: name,  email: email, password: password})
  .then(data => {
    var contentStatus = document.querySelector('.content-messaje')
    if (data.status == 201) {
      form.querySelector('#name').value = ''
      form.querySelector('#email').value = ''
      contentStatus.classList.add('ok-messaje')
      contentStatus.textContent = data.messaje
      setTimeout(() => {
        contentStatus.classList.remove('ok-messaje')
        contentStatus.textContent = ''
      }, 1000);
    }else {
      contentStatus.classList.add('error-messaje')
      contentStatus.textContent = data.messaje
      setTimeout(() => {
        contentStatus.classList.remove('error-messaje')
        contentStatus.textContent = ''
      }, 1000);
    }
    form.querySelector('#password').value = ''
  });
}
// Ejemplo implementando el metodo POST:
async function postData(url = '', data = {}) {
  // Opciones por defecto estan marcadas con un *
  return new Promise(async (resolve, rejects) => {
    const response = await fetch(url, {
      method: 'POST', // *GET, POST, PUT, DELETE, etc.
      mode: 'cors', // no-cors, *cors, same-origin
      cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
      credentials: 'same-origin', // include, *same-origin, omit
      headers: {
        'Content-Type': 'application/json'
        // 'Content-Type': 'application/x-www-form-urlencoded',
      },
      redirect: 'follow', // manual, *follow, error
      referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
      body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    resolve(response.json()); // parses JSON response into native JavaScript objects
  })
}
