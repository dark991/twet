<?php

$host = 'http://virtualbox.vm';
//$host = 'http://192.168.43.152';

return new \Phalcon\Config(
    [
        'mail' => [
            'fromName' => 'Twet',
            'fromEmail' => 'twet-bot@virtualbox.vm',
        ],
        'hosts' => [
            'main' => $host,
        ],
        'twitch' => [
            'client_id' => '',
            'client_secret' => '1',
            'redirect_uri' => $host . '/twitch/callback',
        ],
        'crypt' => [
            'key' => '',
        ],
        'mysql' => [
            'host' => 'virtualbox.vm',
            'username' => '',
            'password' => '',
            'dbname' => 'twet',
            'persistent' => 'false',
        ],
    ]
);