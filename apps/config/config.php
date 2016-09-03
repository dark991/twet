<?php

$host = 'http://virtualbox.vm';

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
    ]
);

//
// Twet api Client ID (twitch)
// ru0xspiy28g46max3mqdxuywpc4za16
//

/// alex_lifar stream key
/// live_113272096_2AIjlE0FQv2nEKSv4KR6mg83rVzzTf