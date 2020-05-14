<?php

function get_db_config()
{
    if (getenv('IS_IN_HEROKU')) {
        // postgreSQL 数据库
        $url = parse_url(getenv('DATABASE_URL'));

        return $db_config = [
            'connection'    => 'pgsql',
            'host'          => $url['host'],
            'database'      => substr($url['path'],1),
            'username'      => $url['user'],
            'password'      => $url['pass'],
        ];
    } else {
        //mysql 数据库
        return $db_config = [
            'connection'    =>  env('DB_CONNECTION','mysql'),
            'host'          =>  env('DB_HOST','localhost'),
            'database'      =>  env('DB_DATABASE','weibo'),
            'username'      =>  env('DB_USERNAME','homestead'),
            'password'      =>  env('DB_PASSWORD','secret'),
        ];
    }
}
