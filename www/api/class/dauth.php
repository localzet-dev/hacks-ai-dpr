<?php 
class Dauth
{

    public $auth_version = '1'; // Версия протокола авторизации
    private $jwt_key = 'xob[]dn>yWXwM8W#>DA?o-_/Yh$e5LBM>(?:B_IR`mYmR[.cS2|m^;(&6/un|012';  // Соль для шифрования jwt
    private $cookieTokenName = 'authToken';
    private $cookieSendToName = 'sendTo';
    public $user = array();

    public function is_auth()
    {
        // Проверка на авторизацию

        $token = addslashes(!empty($_COOKIE[$this->cookieTokenName]) ? $_COOKIE[$this->cookieTokenName] : ''); // получаем данные о токене

        if ($token == '') {
            return false;
        } else {
            // Проверка на авторизацию
            $jwt_check = $this->jwt_check($token);
            if ($jwt_check['auth']) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function jwt_check($jwtToken = '')
    {
        // Проверка данных JWT

        $ans = array('auth' => false); // Данные для возрата

        $jwtArr = array_combine(['header', 'payload', 'signature'], explode('.', $jwtToken)); // Разбиваем токен

        $jwtHash = $jwtArr['signature'];
        $calculatedHash = hash_hmac( // считаем хеш
            'sha256',
            $jwtArr['header'] . '.' . $jwtArr['payload'],
            $this->jwt_key,
            true
        );
        $calculatedHash = base64_encode($calculatedHash);
        $calculatedHash = $this->base64_url_encode(hash_hmac('sha256', $jwtArr['header'] . '.' . $jwtArr['payload'], $this->jwt_key, true));

        if ($calculatedHash == $jwtHash) {
            // Случилось
            try {
                $ans['data'] = json_decode(base64_decode($jwtArr['payload']), true); // декодированный токен
                $ans['error'] = false;
                $ans['auth'] = true;
            } catch (Exception $e) {
                $ans['error'] = true;
                $ans['error_data'] = $e;
                $ans['mess'] = 'error_decode_json';
            }
        } else {
            // Не случилось
            $ans['error'] = true;
            $ans['mess'] = 'error_signature_hash';
        }

        return $ans;
    }

    private function jwt_create($auth_status, $user_id, $hash, $is_admin = false, $is_cookie = true)
    {
        // Создание токена 

        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
        $header = $this->base64_url_encode(json_encode($header));
        $payload =  [
            "date" => Defs::time(),
            "authStatus" => $auth_status,
            'hash' => $hash,
            "user_id" => $user_id,
            "is_admin" => $is_admin
        ];
        $payload = $this->base64_url_encode(json_encode($payload));
        $signature = $this->base64_url_encode(hash_hmac('sha256', "$header.$payload", $this->jwt_key, true));
        $jwt = "$header.$payload.$signature";
        if ($is_cookie) {
            setcookie($this->cookieTokenName, $jwt, time() + 3600 * 24 * 365, '/', Url::$domain);
            return '';
        } else {
            return $jwt;
        }
    }

    private function base64_url_encode($text): String
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }

    public function sendto($url = '', $is_set = false)
    {
        // Получение URL адрес конечной страницы

        if ($is_set) {
            setcookie($this->cookieSendToName, addslashes($url), time() + 3600 * 24, '/', Url::$domain);
        } else {
            return !empty($_COOKIE[$this->cookieSendToName]) ? $_COOKIE[$this->cookieSendToName] : '';
        }
    }

    private function getUserIdByOperId($oper, $oper_id = '')
    {
        // Поиск данных о пользователе основываясь

        $oper_lable = addslashes($oper) . '_id';

        $sql = "SELECT * FROM users WHERE " . $oper_lable . " = '" . addslashes($oper_id) . "'";
        //echo($sql);
        //die();
        $user = DB::query($sql, true);
        return $user;
    }

    public static function logout()
    {
        // РазАвторизация (Выход)
        setcookie('sendto', Url::$path, time() + 3600 * 24, '/', Url::$domain);
        setcookie('authToken', '', time(), '/', Url::$domain);
        header('Location: '.Url::$path);
        die();
    }

    public static function getUserImage_ByName($name){
        return 'https://ui-avatars.com/api/?size=512&background=random&name='.$name;
    }

    private function registration($user_data, $oper, $hi = true)
    {
        // Регистрация

        $rows = array();

       

        // Вносим в БД
        $sql = "INSERT INTO users(first_name, last_name, other_name, nickname, edu_id, exesfull_id, mye_id, is_admin, email, phone, auth, hash, zach_code, img_url) VALUES ('".addslashes($rows['first_name'])."','".addslashes($rows['last_name'])."','".addslashes($rows['other_name'])."','".addslashes($rows['nickname'])."','".addslashes($rows['edu_id'])."','".addslashes($rows['exesfull_id'])."','".addslashes($rows['mye_id'])."','".addslashes($rows['is_admin'])."','".addslashes($rows['email'])."','".addslashes($rows['phone'])."','".addslashes($rows['auth'])."','".addslashes($rows['hash'])."','".addslashes($rows['zach_code'])."','".addslashes($rows['img_url'])."')";
          /*  $sql = $sql.'"' . addslashes($rows['other_name']) . '",';
            $sql = $sql.'"' . addslashes($rows['nickname']) . '",';
            $sql = $sql.'"' . addslashes($rows['edu_id']) . '",';
            $sql = $sql.'"' . addslashes($rows['exesfull_id']) . '",';
            $sql = $sql.'"' . addslashes($rows['mye_id']) . '",';
            $sql = $sql.'"' . addslashes($rows['is_admin']) . '",';
            $sql = $sql.'"' . addslashes($rows['email']) . '",';
            $sql = $sql.'"' . addslashes($rows['phone']) . '",';
            $sql = $sql.'"' . addslashes($rows['auth']) . '",';
            $sql = $sql.'"' . addslashes($rows['hash']) . '",';
            $sql = $sql.'"' . addslashes($rows['zach_code']) . '",';
            $sql = $sql.'"' . addslashes($rows['img_url']) . '"';
            $sql = $sql.');';*/
        DB::query($sql); 

        // Получаем ID
        $sql = "SELECT * FROM users WHERE hash = '" . $rows['hash'] . "' AND first_name = '" . $rows['first_name'] . "' ORDER BY date DESC LIMIT 1";
        $user = DB::query($sql, true);

        return array(
            'status' => true,
            'user_id' => $user[0],
            'user' => $user 
        );
    }

    public function GetUsersAccess()
    {
        $sql = "SELECT sec_access_service.id AS serviceID, sec_access_service.title, sec_access_service.description, sec_access_service.is_admin, sec_access_service.login, sec_access_service.img_url, sec_access_service.is_sov FROM sec_access_users JOIN sec_access_service ON sec_access_users.access_id = sec_access_service.id WHERE sec_access_users.user_id='" . $this->user['id'] . "' AND sec_access_service.status = 'true'";
        $arr = DB::query($sql, true);
        return $arr;
    }

    public function GetUserTokenInfo()
    {
        $token = addslashes(!empty($_COOKIE[$this->cookieTokenName]) ? $_COOKIE[$this->cookieTokenName] : '');
        if ($token == ''){
            echo('error: GetUserTokenInfo');
            die();
        }
        $jwtArr = array_combine(['header', 'payload', 'signature'], explode('.', $token)); // Разбиваем токен
        $data = json_decode(base64_decode($jwtArr['payload']), true);
        return $data;
    }

    public function GetUser()
    {
        $token_info = $this->GetUserTokenInfo();
        $sql = "SELECT * FROM wcpt.users WHERE id = '" . $token_info['user_id'] . "'";
        $this->user = DB::query($sql, true);
        $this->user['img_url'] = $this->getUserImage_ByName($this->user['first_name']);
        return $this->user;
    }

    public function reauth()
    {
        //Функция Ре-авторизации

        //$this->sendto(Url::now_url(), true);
        //$sso = new Sso();
        /*
        $oper = 'exesfull';

        if ($oper == 'exesfull') {
            //$data = $sso->exesfull();
            $user = $this->getUserIdByOperId($oper, $data['eid']);

            if (!$user) {
                $user = $this->registration($data, $oper)['user'];
            }

            $this->jwt_create($user['auth'], $user['id'], $user['hash'], $user['is_admin']);
        }
        */
        header('Location: ' . 'https://wcpt.exesfull.com/');
        die();
        
    }

    private function calculateHash($text){
        $hash = hash('sha256', $password.'_'.$this->jwt_key);
        //echo($hash);
        //die();
        return $hash;
    }

    public function enter_by_lap($login, $password){
        $hash = $this->calculateHash($password);

        $sql = "SELECT wcpt.users.id, wcpt.users.create_date, wcpt.users.status FROM wcpt.users WHERE wcpt.users.email = '".$login."' AND wcpt.users.passhash = '".$hash."'";
        $result = DB::query($sql, true);


        if ($result){
        
            $this->jwt_create($result['status'], $result['id'], $result['create_date']);

            return true;
        }else{
            return false;
        }

        return $result;
    }

    public function reauth_page()
    {
        //Функция Ре-авторизации Страница

        $this->sendto(Url::now_url(), true);

        $oper = (!empty($_REQUEST['oper']) ? $_REQUEST['oper'] : '');
        if ($oper == ''){
            $code = (!empty($_REQUEST['code']) ? $_REQUEST['code'] : '');
            if ($code != ''){
                $oper = 'edu';
            }
        }

        if ($oper != ''){
            // Ре-авторизация пользователя
            $sso = new Sso();
            
            //$oper = 'exesfull';
            if ($oper == 'exesfull') {
                $data = $sso->exesfull();
                $user = $this->getUserIdByOperId($oper, $data['eid']);

                if (!$user) {
                    $user = $this->registration($data, $oper)['user'];
                }

                $this->jwt_create($user['auth'], $user['id'], $user['hash'], $user['is_admin']);
            }else if($oper == 'mye'){
                $data = $sso->oauth_mye();

                $data = $data['response']['data'];
                $user = $this->getUserIdByOperId($oper, $data['id']);
               
                if (!isset($user['id'])) {
                    $user = $this->registration($data, $oper, false)['user'];
                }

                $this->jwt_create($user['auth'], $user['id'], $user['hash'], $user['is_admin']);
            }else if($oper == 'edu'){
                $data = $sso->oauth_edu();
                $user = $this->getUserIdByOperId($oper, $data['user']['userID']);
               
                if (!isset($user['id'])) {
                    $user = $this->registration($data, $oper, false)['user'];
                }

                $this->jwt_create($user['auth'], $user['id'], $user['hash'], $user['is_admin']);
            }

            header('Location: ' . $this->sendto());
            die();
        } else {
            include_once __DIR__ . "/../../pages/elements/main.php";
            $file = '
                <!DOCTYPE html>
                <html lang="ru">
                <head>
                    ' . pages_main_element_headcode() . '
                    <title>Авторизация</title>
                    <style>
                    .app-auth-sign-in .app-auth-background {
                        background: url(https://upload.wikimedia.org/wikipedia/commons/9/91/Coat_of_Arms_of_the_Donetsk_People%27s_Republic.svg) no-repeat;
                        background-size: 60%;
                        background-position: center;
                    }
                    .app-auth-container .logo a {
                        display: flex;
                        padding-left: 70px;
                        background: url(https://wcpt.exesfull.com/favicon.ico) no-repeat;
                        height: 50px;
                        background-size: 50px;
                        align-items: center;
                        text-decoration: none;
                        color: #40475c;
                        font-size: 21px;
                        font-weight: bold;
                    }
                    </style>
                </head>
                <body> 
                    <div class="app app-auth-sign-in align-content-stretch d-flex flex-wrap justify-content-end">
                        <div class="app-auth-background">

                        </div>
                        <div class="app-auth-container">
                            <div class="logo">
                                <a href="">Контролируем цены вместе</a>
                            </div>
                            <div id="form_auth">
                                <p class="auth-description">Если у вас нет аккаунта, вы можете <a class="text-primary" style="text-decoration:unset;"  onclick="page_view_register();">Создать </a>его прямо сейчас!</p>
                                <div id="error_text"></div>
                                <div class="auth-credentials m-b-xxl">
                                    <label for="signInEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control m-b-md" id="auth_login" aria-describedby="signInEmail">

                                    <label for="signInPassword" class="form-label">Пароль</label>
                                    <input type="password" class="form-control" id="auth_password" aria-describedby="signInPassword" placeholder="">
                                </div>

                                <div class="auth-submit">
                                    <a onclick="api_auth_enter_by_login_password();" class="btn btn-primary">Войти</a>
                                    <a style="text-decoration:unset;" href="#" class="auth-forgot-password float-end">Забыл пароль?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ' . pages_main_element_footercode() . '
                    <script src="https://wcpt.exesfull.com/assets/js/api/auth.js"></script>
                </body>
                </html>
            ';
            echo ($file);
        }
    }
}

?>