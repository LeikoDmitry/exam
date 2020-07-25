<?php
return [
    'adapter' => [
        'host'     => getenv('HOST'),
        'database' => getenv('MYSQL_DATABASE'),
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD')
    ]
];