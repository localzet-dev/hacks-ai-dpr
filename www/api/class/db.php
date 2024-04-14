<?php

class DB{
    public static function query($query, $only_one = false){
        $dbconn = pg_connect("host=194.67.112.62 dbname=postgres user=postgres password=GH2UR3il8F5T7Ycfh3gvj343hkFTE3")
            or die('Не удалось соединиться: ' . pg_last_error());

        // Выполнение SQL-запроса
        $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
        //$arr = pg_fetch_array($result);

        // Вывод результатов в HTML
        $ans = array();
        if ($only_one){
            return pg_fetch_array($result, null, PGSQL_ASSOC);
        }
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $ans[] = $line;
        }

        pg_free_result($result);
        pg_close($dbconn);
        return $ans;
    }
}

?>