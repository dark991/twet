<?php

//$host = 'http://virtualbox.vm';
$host = 'http://192.168.43.152';

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
            'client_id' => 'ru0xspiy28g46max3mqdxuywpc4za16',
            'client_secret' => '1',
            'redirect_uri' => $host . '/twitch/callback',
        ],
        'crypt' => [
            'key' => 'd~aX%}x4<*ZxDX_vL%Hn',
        ],
        'mysql' => [
            'host' => '192.168.43.152',
            'username' => 'twet_user',
            'password' => 'sandMAX.1',
            'dbname' => 'twet',
            'persistent' => 'false',
        ],
    ]
);

//
// Twet api Client ID (twitch)
// ru0xspiy28g46max3mqdxuywpc4za16
//

/// alex_lifar stream key
/// live_113272096_2AIjlE0FQv2nEKSv4KR6mg83rVzzTf

// mysql----
// twet_user
// sandMAX.1