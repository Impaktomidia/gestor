<?php

$env = 'uol'; // ou 'uol'

if ($env === 'uol') {
    return [
        'host' => 'ipk2024.mysql.uhserver.com',
        'db'   => 'ipk2024',
        'user' => 'ipk',
        'pass' => 'Ipk@12647',
    ];
} else {
    return [
        'host' => 'localhost',
        'db'   => 'ipk2024',
        'user' => 'root',
        'pass' => '',
    ];
}
