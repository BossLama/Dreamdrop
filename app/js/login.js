
function togglePopup()
{
    let create = document.getElementById('popup-wrapper-create');
    let login = document.getElementById('popup-wrapper-login');

    create.classList.toggle('hidden');
    login.classList.toggle('hidden');
}

function loginGroup()
{
    let formdata = new FormData(document.getElementById('form_login'));
    fetch('./app/php/login_dreamdrop_group.php', {
        method: 'POST',
        body: formdata
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        data = JSON.parse(data);
        if (data.status === 'success') {
            window.location.href = './index.html';
        } else {
            alert('Error: ' + data.message);
        }
    })
}

function createGroup()
{
    let formdata = new FormData(document.getElementById('form_create'));
    fetch('./app/php/create_dreamdrop_group.php', {
        method: 'POST',
        body: formdata
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        data = JSON.parse(data);
        if (data.status === 'success') {
            window.location.href = './index.html';
        } else {
            alert('Error: ' + data.message);
        }
    })
}