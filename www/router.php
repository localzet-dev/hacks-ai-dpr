<?php


function router()
{

    $auth = new Dauth();
    //return $auth->is_auth();
    if (!$auth->is_auth()) {
        $auth->reauth_page();
    }
    $GLOBALS['TokenInfo'] = $auth->GetUserTokenInfo();
    $GLOBALS['user'] = $auth->GetUser();

    if ($GLOBALS['TokenInfo']['authStatus'] != $GLOBALS['user']['status']) {
        $auth->reauth();
    }

    if ($GLOBALS['TokenInfo']['authStatus'] == 'true') {
        //$GLOBALS['acces'] = $auth->GetUsersAccess();
        $url = Url::now_path();
        $url_ps = Url::$url_p_start;
        if ($url[0] == '/') {
            $url = substr($url, 1);
        }
        if ($url == '') {
            return mainpages_dashboard();
        } else {
            
            $url = $url . '/';
            if (strpos($url, '/')) {
                $url_p = explode('/', $url);
                if ($url_p[$url_ps + 0] == '') {
                    return mainpages_dashboard();
                }else if ($url_p[$url_ps + 0] == 'vizor') {
                    return mainpages_vizor();
                } else {
                    return error_404();
                }
            } else {
                return 'ошибка при делении';
            }
        }
    } else if ($GLOBALS['TokenInfo']['authStatus'] == 'hi') {

        return mainpages_auth_hi();
    } else {
        return '<h1>Ваш профиль деактивирован</h1>';
    }
}
