function api_new_inspection_start(){
    window.auth_key = 'jwt_auth';
    window.swiper_styles = false;

    api_new_inspection_page_main();
    
}

function api_new_inspection_set_body(html = ' '){
    // Задает код в содержание страницы
    document.getElementById('main_block').innerHTML = html;
}
function api_new_inspection_set_loader(text = 'Загрузка...'){
    // Задает код в содержание страницы
    var block = `
    <div id="documentation"> 
    <div class="section-title container ml-auto mr-auto py-5">
        <div class="col-lg-8 col-md-10 ml-auto mr-auto py-5">
            <h4 class="mb-2 text-center"><img style="width:25%" src="https://tu.exesfull.com/new/swiper/img/loader.gif"></h4>
            <h3 class="mb-4 text-center">`+text+`</h3>
        </div>
    </div>
</div>
    `;
    api_new_inspection_set_body(block);
}

function api_new_inspection_set_header(html = ' '){
    // Задает код в содержание страницы
    document.getElementById('header_block').innerHTML = html;
}

function api_new_inspection_page_element_header(){
    var html =  ` 
        <div class="container">
            <nav style="border-bottom-right-radius: 20px;border-bottom-left-radius: 20px"  class="navbar navbar-expand-lg navbar-light bg-light mb-4"><img src="https://wcpt.exesfull.com/favicon.ico" class="mr-2" height="30"> <a class="navbar-brand" href="https://tu.exesfull.com/"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown-7" aria-controls="navbarNavDropdown-7" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown-7">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="https://wcpt.exesfull.com/" onclick="api_new_inspection_page_main();">Главная <span class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="https://wcpt.exesfull.com/">Мои заявки</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://wcpt.exesfull.com/api/auth/api.php?api=api_auth_logout">Выйти</a></li>
                </ul>
                <ul class="navbar-nav">
                    <div id="chan_bar_auth">
                        <a href="https://wcpt.exesfull.com/" class="btn btn-outline-primary btn-pill align-self-center">Личный кабинет</a>
                    </div>
                </ul>
                </div>
            </nav>
        </div>
    </header>`;
    api_new_inspection_set_header(html);

}

function api_view_image_price(){
    //var file = document.getElementById('customF11ile').value;
    /*document.getElementById('view_price_image').innerHTML = `
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4 sm-hidden">
        <div class="card">
            <img class="card-img-top" id="price_image" alt="Картинка ценника" />
            <div class="card-body">
                <h4 class="card-title">Ценник товара</h4>
            </div>
        </div>
    </div>`;

    let f = document.getElementById('customF11ile').files[0];
        if (f) {
            document.getElementById('price_image').src = URL.createObjectURL(f);
            localStorage.setItem('myImage', document.getElementById('kprice_imagell').src);
            document.getElementById('price_image').src = localStorage.getItem('myImage')
        }
    */
}

function api_new_inspection_page_main(){
    // Главная страница
    api_new_inspection_page_element_header();
    var html = `
    <div class="page-content">
        <div class="content clearfix">
            <div id="cards" class="container mb-2" style="padding-bottom: 1px">
                <div class="section-title col-lg-8 col-md-10 ml-auto mr-auto">
                    <h3 class="mb-4"> <img style="width: 10%;" src="https://tu.exesfull.com/new/swiper/img/eye.gif" alt="Card image cap"> Проверка цены</h3>
                    <p><span style="color:red">*</span> - Обязательно для заполнения</p>
                    <hr>
                    <div class="col-md-8 pl-0">
                        <h6 class="text-muted mb-3"><span style="color:red">*</span> Добавьте фотографию ценника товара</h6>
                        <fieldset>
                            <div class="custom-file w-100">
                                <input onchange="api_view_image_price();"  type="file" class="custom-file-input" id="customF11ile">
                                <label class="custom-file-label" for="customFile">Фото ценника </label>
                            </div>
                            <div id="view_price_image"></div>
                        </fieldset>
                        <fieldset>
                            <div class="custom-control custom-checkbox d-block my-2">
                                <input type="checkbox" class="custom-control-input" id="customCheck1"> 
                                <label class="custom-control-label" for="customCheck1">У товара нет ценника (названия / цены)</label>
                            </div>
                        </fieldset>
                    </div>
                    <hr>
                    <!--<div class="col-md-8 pl-0">
                        <h5 class="text-muted mb-3" Добавьте фотографию полки с товаром</h5>
                        <fieldset>
                            <div class="custom-file w-100">
                                <input type="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Фото полки </label>
                            </div>
                        </fieldset>
                    </div>-->

                    <button type="button" class="btn btn-primary btn-pill btn-lg d-table ml-auto mr-auto">Отправить</button>
                        
                    
                </div>
            </div>
        </div>
        <br><br>
    </div>
    `;
    api_new_inspection_set_body(html);

    api_inspection_get_list_cr();

}

function api_new_inspection_page_stat(){
    api_new_inspection_page_element_header();
    var html = `
    <div class="page-content">
        <div class="content clearfix">
            <div id="cards" class="container mb-2" style="padding-bottom: 1px">
                <div class="section-title col-lg-8 col-md-10 ml-auto mr-auto">
                    <h3 class="mb-4"> <img style="width: 10%;" src="https://tu.exesfull.com/new/swiper/img/eye.gif" alt="Card image cap"> Статистика</h3>
                    <p>Электронная система проверки комнат (v2)</p>
                    <hr>
                    <center>
                        <h2>Всего <span id="count_raports"></span></h2>
                        <button type="button" class="btn btn-primary" onclick="api_inspection_get_list_stat();">Обновить</button>
                    </center>
                    <hr>
                    <br>
                    <div id="inf"></div>
                    <div class="row" id="stats"></div>
                </div>
              
            </div>
           
        </div>
    </div>`;
    api_new_inspection_set_body(html);
    api_inspection_get_list_stat();

}

function api_inspection_get_list_stat(){
    var form = new FormData();
    document.getElementById('inf').innerHTML = ' ';
    document.getElementById('stats').innerHTML = ' ';
	form.append('api', 'api_inspection_get_list_stat');
	axios.post('https://tu.exesfull.com/new/dhall/api.php', form)
		.then(function (response) {
			var url = '';
			var block = ``;
            response.data.sort(function (a, b) {
                return a.level_id - b.level_id || a.room_id - b.room_id;
            });
            count_raports = response.data.length;
            response.data.forEach((s) => {
                if (s['room_id'].length == 1){
                    r = '0'+s['room_id'];
                }else{
                    r = s['room_id'];
                }
                block = block + `
                <div class="col-lg-12 col-md-6 col-sm-12 mb-4">
                    <div onclick="api_inspection_get_room_list_stat('`+s['set_id']+`')" class="card" style="border-radius: 20px;">
                        <div class="card-body" style="min-width: 300px;">
                            <h4 class="card-title">`+s['level_id']+r+` - <code>`+s['mark']+` %</code></h4>
                        </div>
                    </div>
                </div>`;
            });
            
            document.getElementById('stats').innerHTML = block;
            document.getElementById('count_raports').innerHTML = count_raports;
        }).catch(function (error) {
            console.log(error);
        });
}

function api_inspection_get_room_list_stat(set_id){
    var form = new FormData();
	form.append('api', 'api_inspection_get_room_list_stat');
    form.append('set_id', set_id);
	axios.post('https://tu.exesfull.com/new/dhall/api.php', form)
		.then(function (response) {
			var block = ``;
            
            response.data['ra'].forEach((s) => {
                if (s['value'] == '2'){
                    color = 'green';
                }else{
                    color = 'red';
                }

                block = block + `
                <div class="col-lg-12 col-md-6 col-sm-12 mb-4">
                    <div onclick="" class="card" style="border-radius: 20px;">
                        <div class="card-body" style="min-width: 300px;">
                            <code>`+s['info']['c']['title']+`</code>
                            <h4 style="color: `+color+`" class="card-title">`+s['info']['r']['title']+`</h4>
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('inf').innerHTML = `
                <h4 onclick="api_inspection_get_list_stat();">← Назад</h4>
                
                <center><h4>`+response.data['n']+`</h4></center>
                <p>Комментарий: `+response.data['c']+`</p>
                <center><h4 onclick="api_inspection_delete('`+response.data['s']+`');" style="color:red">Удалить</h4></center>
                <hr>
            `;
            document.getElementById('stats').innerHTML = block;
        }).catch(function (error) {
            console.log(error);
        });
}

function api_inspection_delete(set_id){
    var form = new FormData();
    window.raiting = {}
	form.append('api', 'api_inspection_delete');
    form.append('set_id', set_id);
	axios.post('https://tu.exesfull.com/new/dhall/api.php', form)
		.then(function (response) {
            api_inspection_get_list_stat();
        }).catch(function (error) {
            console.log(error);
        });
}

function api_inspection_get_list_cr(){
    var form = new FormData();
    window.raiting = {}
	form.append('api', 'api_inspection_get_list_cr');
	axios.post('https://tu.exesfull.com/new/dhall/api.php', form)
		.then(function (response) {
			var url = '';
			var block = ``;
            
            response.data.forEach((s) => {
                window.raiting[s['r']['ID']] = false;
                block = block + `
                <div class="col-lg-12 col-md-6 col-sm-12 mb-4">
                    <div class="card" style="border-radius: 20px;">
                        <div class="card-body" style="min-width: 300px;">
                            <code>`+s['c']['title']+`</code>
                            <h4 class="card-title">`+s['r']['title']+`</h4>
                            <p class="card-text">`+s['r']['description']+`</p>
                            <label style="right:5%;position:absolute;bottom:30%" class="checkbox-ios">
                                <input name="`+s['r']['ID']+`" onchange="api_inspection_set_raiting_mark(this.name);" type="checkbox">
                                <span class="checkbox-ios-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('raits').innerHTML = block;
        }).catch(function (error) {
            console.log(error);
        });
}

function api_inspection_set_level_id(id){
    window.level_id = id;
    api_inspection_view_room_num();
}

function api_inspection_set_room_id(id){
    window.room_id = id;
    api_inspection_view_room_num();
}

function api_inspection_view_room_num(){
    if ((window.level_id !=undefined)&&(window.room_id != undefined)){
        if (window.room_id.length == 1){
            r = '0'+window.room_id
        }else{
            r = window.room_id;
        }
        document.getElementById('view_num_room').innerHTML = '№ '+window.level_id+''+r;
        api_inspection_add_save_button();
    }
}

function api_inspection_set_raiting_mark(r_id){
    if (window.raiting[r_id]){
        window.raiting[r_id] = false;
    }else{
        window.raiting[r_id] = true;
    }
    //alert(r_id+' '+value);
}

function api_inspection_add_save_button(){
    document.getElementById('save_button_block').innerHTML = `<button type="button" class="btn btn-primary btn-pill btn-lg d-table ml-auto mr-auto" onclick="api_inspection_save_data();">Сохранить</button>`;
}

function api_inspection_save_data(){
    var data = {}
    data['raiting'] = window.raiting;
    data['level_id'] = window.level_id;
    data['room_id'] = window.room_id;
    data['comment'] = document.getElementById('cooment').value;
    data = JSON.stringify(data);

    var form = new FormData();
	form.append('api', 'api_inspection_save_data');
    form.append('data', data);
	axios.post('https://tu.exesfull.com/new/dhall/api.php', form)
		.then(function (response) {
            if (window.room_id.length == 1){
                r = '0'+window.room_id
            }else{
                r = window.room_id;
            }
            Swal.fire({
                title: "Сохранено",
                text: "Комната № "+window.level_id+r,
                icon: "success"
              }).then((result) => {
                location.reload();
              });
    }).catch(function (error) {
        console.log(error);
    });
}