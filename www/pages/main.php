<?php

include_once "elements/main.php";
include_once "vizor.php";

function mainpages_auth_hi(){
$file = '
  <html class="no-js" lang="ru">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<title>Авторизация</title>
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/bst.css?v=1.1.0" />
		<link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/fa.css?v=1.1.0" />
		<link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/shards.min.css" />
		<link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/shards-demo.css?v=1.1.0" />
		<link rel="icon" type="image/png" href="'.Url::$favicon.'" />
		<script src="https://an.exesfull.com/analytics/main.js"></script>
		<!--<link rel="manifest" href="swiper.webmanifest" />-->
		<style>
			.welcome{ }
		</style>
	</head>
	<body>
		<header id="header_block" class="sticky-header">
			<div class="container">
				<nav style="border-bottom-right-radius: 20px;border-bottom-left-radius: 20px" class="navbar navbar-expand-lg navbar-light bg-light mb-4">
					<img src="'.Url::$favicon.'" class="mr-2" height="30" /> <a class="navbar-brand" href="https://hall.donstu.ru/">СтудГородок</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown-7" aria-controls="navbarNavDropdown-7" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNavDropdown-7">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item active">
								<a class="nav-link" href="https://donhall.exesfull.com/">Главная <span class="sr-only">(current)</span></a>
							</li>
							<li class="nav-item"><a class="nav-link" href="https://vk.com/sgdonstu">ВКонтакте</a></li>
							<li class="nav-item"><a class="nav-link" href="https://donstu.ru/">ДГТУ</a></li>
						</ul>
						<ul class="navbar-nav">
							<div id="chan_bar_auth"><a href="https://donhall.exesfull.com/system/auth/logout/" class="btn btn-outline-primary btn-pill align-self-center">Выйти</a></div>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<div id="main_block">
			<div class="page-content">
				<div class="content clearfix">
					<div id="cards" class="container mb-2" style="padding-bottom: 1px">
						<div class="section-title col-lg-8 col-md-10 ml-auto mr-auto">
							<h3 class="mb-4"><img style="width: 7%;" src="https://emojigraph.org/media/apple/waving-hand_1f44b.png" alt="Hello" /> Здравствуйте, '.$GLOBALS['user']['first_name'].'</h3>
							<p>! Ваш аккаунт, пока, не привязан к инфраструктуре ДонГосТеха</p>
                            <h4>Обратитесь к администратору, для верификации аккаунта</h4>
							<hr/>
						</div>
					</div>
				</div>
				<br />
				<br />
			</div>
		</div>
		<script async="" defer="defer" src="https://buttons.github.io/buttons.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
		<script src="https://pay.exesfull.com/assets_select/js/shards.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
		<script src="https://pay.exesfull.com/assets_select/js/bst.js"></script>
		<script src="https://assets.exesfull.com/js/s2a.js"></script>
		<script src="https://walls.exesfull.com/js/api/walls.js"></script>
		<script>api_walls_get_list();</script>
	</body>
</html>';

return $file;
}

function error_404(){

	$file = '<!doctype html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>СтудГородок</title>

      '.pages_main_styles_css().'

  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">


      <div class="wrapper">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>

<div class="gradient">
    <div class="container">
        <img src="https://tu.exesfull.com/assets/images/error/404.png" class="img-fluid mb-4 w-50" alt="">
        <h2 class="mb-0 mt-4 text-white">ОЙ! Ничего не найдено.</h2><br>
        <a class="btn bg-white text-primary d-inline-flex align-items-center" href="'.Url::$path.'">Главная</a>
    </div>
    <div class="box">
        <div class="c xl-circle">
            <div class="c lg-circle">
                <div class="c md-circle">
                    <div class="c sm-circle">
                        <div class="c xs-circle">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
      </div>

    '.pages_main_scripts_js().'
  </body>
</html>';
return $file;
}

function mainpages_dashboard(){
	// главная страница личного кабинета

$file = '<!DOCTYPE html>
<html lang="ru">
<head>
	'.pages_main_element_headcode().'
	<title>Главная</title>
</head>
<body>
	<div class="app align-content-stretch d-flex flex-wrap">
		'.pages_main_element_menu().'
		<div class="app-container">
			'.pages_main_element_head().'
			<div class="app-content">
				<div class="content-wrapper">
					<div class="container-fluid">
						<div class="row">
							<div class="col">
								<div class="page-description page-description-tabbed">
									<h1>Контролируем цены вместе</h1>
								</div>
							</div>
						</div>
						
						<div class="row">
							<a style="text-decoration:unset;" href="https://wcpt.exesfull.com/vizor/">
								<div class="col-xl-12">
									<div style="margin:unset; border-radius: 10px; padding: 40px 30px; background: #2269f3; display: block;" class="invoice-header">
										<div class="row">
											<div class="col-9">
												<h3>Давайте проверим товар прямо сейчас!</h3>
											</div>
											<div class="col-3">
												<h5 class="invoice-issue-date">Нажмите, чтобы начать</h5>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<br><br>

						<div class="row">
							<div class="col-xl-4">
								<div class="card widget widget-info">
									<div class="card-body">
										<div class="widget-info-container">
											<div class="widget-info-image" style="border-radius: 10%; background: url(https://my.e.donstu.ru/t/dormitory/assets/images/favicon.ico);background-size: 100px !important; "></div> 
											<h5 class="widget-info-title">Логотип!</h5>
											<p class="widget-info-text">Студенческий городок ДГТУ объявляет конкурс на новый логотип! На данный момент используется временный логотип.</p><br>
											<a href="https://my.e.donstu.ru/t/dormitory/assets/images/favicon.ico" class="btn btn-primary widget-info-action">Открыть</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4">
								<div class="card widget widget-info">
									<div class="card-body">
										<div class="widget-info-container">
											<div class="widget-info-image" style="background: url(https://donhall.exesfull.com/assets/images/logo_dom_10_new.png)"></div>
											<h5 class="widget-info-title">Флаги</h5>
											<p class="widget-info-text">Новый дизайн код общежитий! Представляем вашему вниманию обновлённый логотип 10-го общежития ДонГосТеха!</p>
											<a href="https://donhall.exesfull.com/assets/images/logo_dom_10_new.png" class="btn btn-primary widget-info-action">Открыть</a>
										</div>
									</div>
								</div> 
							</div>
							<div class="col-xl-4">
								<div class="card widget widget-info">
									<div class="card-body">
										<div class="widget-info-container">
											<div class="widget-info-image" style="background: url(https://cdn-icons-png.flaticon.com/512/3141/3141839.png)"></div>
											<h5 class="widget-info-title">Конкурсы</h5>
											<p class="widget-info-text">Объявляем о начале конкурса на заселение в общежития! Если ты живёшь в квартире и хочешь переехать в общежитие, скорее подавай заявку!</p>
											<a href="https://hall.donstu.ru/news/detail.php?CODE=konkurs-na-pereselenie-dlya-prozhivayushchikh-obshchezhitiya-8" class="btn btn-primary widget-info-action">Открыть</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	'.pages_main_element_footercode().'
</body>
</html>';
return $file;
}

function mainpages_profile(){
$file = '<!DOCTYPE html>
<html lang="ru">
<head>
	'.pages_main_element_headcode().'
	<title>Профиль</title>
</head>
<body>
	<div class="app align-content-stretch d-flex flex-wrap">
		'.pages_main_element_menu().'
		<div class="app-container">
			'.pages_main_element_head().'
			<div class="app-content">
				<div class="content-wrapper">
					<div class="container-fluid">
						<div class="row">
							<div class="col">
								<div class="page-description page-description-tabbed">
									<h1>Профиль</h1>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12">
												<div class="avatar avatar-xl m-r-xs"> <img src="'.$GLOBALS['user']['img_url'].'"> </div>
											</div>									
										</div>
										<div class="row m-t-lg">
											<div class="col-md-12">
												<label for="settingsInputFirstName" class="form-label">Имя</label>
												<input readonly type="text" class="form-control" id="user_firstname" placeholder="" value="'.$GLOBALS['user']['first_name'].'"> 
											</div>
                                        </div>
                                        <div class="row m-t-lg">
											<div class="col-md-12">
												<label for="settingsInputLastName" class="form-label">Фамилия</label>
												<input readonly type="text" class="form-control" id="user_lastname" placeholder="" value="'.$GLOBALS['user']['last_name'].'">
											</div>
                                        </div>
                                        <div class="row m-t-lg">
                                            <div class="col-md-12">
												<label for="settingsInputLastName" class="form-label">Отчество</label>
												<input readonly type="text" class="form-control" id="user_othername" placeholder="" value="'.$GLOBALS['user']['other_name'].'">
											</div>
										</div>
                                        <div class="row m-t-lg">
											<div class="col-md-12">
												<label for="settingsInputEmail" class="form-label">Email</label>
												<input type="email" class="form-control" readonly value="'.$GLOBALS['user']['email'].'">
											</div>
										</div>

                                        <div class="row m-t-lg">
											<div class="col-md-12">
												<label for="settingsInputEmail" class="form-label">Номер телефона</label>
												<input type="email" class="form-control" readonly value="'.$GLOBALS['user']['phone'].'">
											</div>
										</div>
									</div>
								</div>		
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	'.pages_main_element_footercode().'
</body>
</html>';
return $file;
}


?>