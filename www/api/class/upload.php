<?php

class Files{

    public function upload($by_exesfull=false, $service_name="", $public=false, $return_url=false){
        if ($public){
            $up_file_type = 'public';
        }else {
            $up_file_type = 'own';
        }
        $file_log = $service_name;
    
    // Название <input type="file">
        $input_name = 'upl';
        $memory_place_for_user = 100;
    // Разрешенные расширения файлов.
        $allow = array();
    
    // Запрещенные расширения файлов.
        $deny = array(
            'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp',
            'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'sql', 'spl', 'scgi', 'fcgi'
        );
    
    // Директория куда будут загружаться файлы.
        $path = '/var/www/exesfull.com/wcpt/assets/upload_images/';
    
        if (isset($_FILES[$input_name])) {
            // Проверим директорию для загрузки.
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
    
            //$sql = 'SELECT SUM(`memory`) FROM `files` WHERE `eid` = "' . t_id() . '"';
            //$sum = driveto($sql, 'SUM(`memory`)');
    
            //$free_place = $memory_place_for_user - ((float)$sum);
            //echo('FP->'.$free_place);
            $z = $_FILES[$input_name]['size'];
            if ($z > 1024 * 10) {
                $zz = round((((float)$z) / (1024 * 1024)), 2, PHP_ROUND_HALF_UP);
            } else {
                $zz = 0.01;
            }
            //echo('FS-->'.$zz);
            // Преобразуем массив $_FILES в удобный вид для перебора в foreach.
            $files = array();
            $diff = count($_FILES[$input_name]) - count($_FILES[$input_name], COUNT_RECURSIVE);
            if ($diff == 0) {
                $files = array($_FILES[$input_name]);
            } else {
                foreach ($_FILES[$input_name] as $k => $l) {
                    foreach ($l as $i => $v) {
                        $files[$i][$k] = $v;
                    }
                }
            }
    
            foreach ($files as $file) {
                $error = $success = '';
    
                // Проверим на ошибки загрузки.
                if (!empty($file['error']) || empty($file['tmp_name'])) {
                    switch (@$file['error']) {
                        case 1:
                        case 2:
                            $error = 'Превышен размер загружаемого файла.';
                            break;
                        case 3:
                            $error = 'Файл был получен только частично.';
                            break;
                        case 4:
                            $error = 'Файл не был загружен.';
                            break;
                        case 6:
                            $error = 'Файл не загружен - отсутствует временная директория.';
                            break;
                        case 7:
                            $error = 'Не удалось записать файл на диск.';
                            break;
                        case 8:
                            $error = 'PHP-расширение остановило загрузку файла.';
                            break;
                        case 9:
                            $error = 'Файл не был загружен - директория не существует.';
                            break;
                        case 10:
                            $error = 'Превышен максимально допустимый размер файла.';
                            break;
                        case 11:
                            $error = 'Данный тип файла запрещен.';
                            break;
                        case 12:
                            $error = 'Ошибка при копировании файла.';
                            break;
                        default:
                            $error = 'Файл не был загружен - неизвестная ошибка.';
                            break;
                    }
                } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                    $error = 'Не удалось загрузить файл.';
                } else {
                    // Оставляем в имени файла только буквы, цифры и некоторые символы.
                    $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
                    $name = mb_eregi_replace($pattern, '-', $file['name']);
                    $name = mb_ereg_replace('[-]+', '-', $name);
    
                    // Т.к. есть проблема с кириллицей в названиях файлов (файлы становятся недоступны).
                    // Сделаем их транслит:
                    $converter = array(
                        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
                        'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
                        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
                        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
                        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
                        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    
                        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
                        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K',
                        'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
                        'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
                        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
                        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
                    );
    
                    $name = strtr($name, $converter);
                    $parts = pathinfo($name);
    
                    if (empty($name) || empty($parts['extension'])) {
                        $error = 'Недопустимое тип файла';
                    } elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
                        $error = 'Недопустимый тип файла';
                    } elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
                        $error = 'Недопустимый тип файла';
                    } else {
                        // Чтобы не затереть файл с таким же названием, добавим префикс.
                        $i = 0;
                        $prefix = '';
                        while (is_file($path . $parts['filename'] . $prefix . '.' . $parts['extension'])) {
                            $prefix = '(' . ++$i . ')';
                        }
                        $name = $parts['filename'] . $prefix . '.' . $parts['extension'];
    
                        // Перемещаем файл в директорию.
                        if (move_uploaded_file($file['tmp_name'], $path . $name . '.txt')) {
                            // Далее можно сохранить название файла в БД и т.п.
                            $success = 'Файл «' . $name . '» успешно загружен.';
    
                            $fs = $file['size'];
                            if ($fs > 1024 * 10) {
                                $fs_f = round((((float)$fs) / (1024 * 1024)), 2, PHP_ROUND_HALF_UP);
                            } else {
                                $fs_f = 0.01;
                            }
    
                            $up_file_memory = (string)($fs_f);
                            if ($by_exesfull){
                                $owner_id = 'img_shere';
                            }else{
                                $owner_id = t_id();
                            }
                            //$sql = 'INSERT INTO `files`(`ID`,`eid`,`name`,`memory`,`file_type`,`logs`, `storage`) SELECT (SELECT MAX(`ID`) FROM `files`)+1 AS `ID`,"' . $owner_id . '" AS `eid` , "' . $name . '" AS `name`, "' . $up_file_memory . '" AS `memory` , "' . $up_file_type . '" AS `file_type`,"'.$file_log.'" AS `logs`, "local_main" AS `storage`';
                            //$a = driveto($sql, '');
                            //$sql = 'SELECT `ID` FROM `files` WHERE `eid` = "' . $owner_id . '" AND `storage` = "local_main" ORDER BY `ID` DESC LIMIT 1';
                            //$file_new_id = driveto($sql, 'ID');
                            rename("/var/www/web/drive/" . $name . '.txt', "/var/www/web/drive/" . $file_new_id . '.txt');
                        } else {
                            $error = 'Не удалось загрузить файл.';
                        }
                    }
                }
                if (!empty($success)) {
                    if ($return_url){
                        return 'https://cloud.exesfull.com/drive/api/download/?file_id='.$file_new_id;
                    }else {
                        return 'ok';
                    }
                } else {
                    if ($return_url){
                        return 'error';
                    }else {
                        return $error;
                    }
                }
            }
        }
    
    }

}

?>