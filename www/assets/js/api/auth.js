function api_auth_enter_by_login_password(){
	var form = new FormData();
	form.append('api', 'api_auth_enter_by_lap');
    form.append('login', document.getElementById('auth_login').value);
    form.append('password', document.getElementById('auth_password').value);
	axios.post('https://wcpt.exesfull.com/api/auth/api.php', form)
		.then(function (response) {
            if (response.data['status']){
                location.reload();
            }else{
                document.getElementById('error_text').innerHTML = '<p style="color:red;">Неверный логин или пароль</p>';
            }
		}).catch(function (error) {
		console.log(error);
	});
}

function page_view_register(){
    var file = `<br><br>
    <div class="auth-credentials m-b-xxl">
        <label for="signUpUsername" class="form-label">Имя</label>
        <input type="text" class="form-control m-b-md" id="signUpUsername" aria-describedby="signUpUsername" placeholder="" />

        <label for="signUpUsername" class="form-label">Фамилия</label>
        <input type="text" class="form-control m-b-md" id="signUpUsername" aria-describedby="signUpUsername" placeholder="" />

        <label for="signUpEmail" class="form-label">Email</label>
        <input type="email" class="form-control m-b-md" id="signUpEmail" aria-describedby="signUpEmail" placeholder="" />

        <label for="signUpPassword" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="signUpPassword" aria-describedby="signUpPassword" placeholder="" />
        <div id="emailHelp" class="form-text">Не забывайте, что пароль - это ваша безопасность</div>
    </div>
    `;
    document.getElementById('form_auth').innerHTML = file;
}