<?php

include_once "../defs.php";

function api_auth_enter_by_lap(){
    $login = addslashes(!empty($_REQUEST['login']) ? $_REQUEST['login'] : '');
    $password = addslashes(!empty($_REQUEST['password']) ? $_REQUEST['password'] : '');

    $dauth = new Dauth();
    $result = $dauth->enter_by_lap($login, $password);

    return json_encode(array(
		'status' => true,
		'data' => $result
	));
}
function api_auth_logout(){
    Dauth::logout();
    header('Location: '.Url::$path);
}

function api_auth_start(){
	// запуск функции API для ЖБК
	$api = !empty($_REQUEST['api']) ? $_REQUEST['api'] : '';

	if ($api == 'api_auth_enter_by_lap'){
		return api_auth_enter_by_lap();
    }else if ($api == 'api_auth_logout'){
		return api_auth_logout();
    }else{
        return json_encode(array(
            'status' => false,
            'error' => 'error_api'
        )); 
    }
}

echo(api_auth_start());
?>