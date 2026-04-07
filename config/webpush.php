<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web Push (VAPID)
    |--------------------------------------------------------------------------
    |
    | Para generar las llaves en producción (donde tengas la extensión sodium):
    |   php artisan tinker
    |   > $k = Minishlink\WebPush\VAPID::createVapidKeys();
    |   > echo "VAPID_PUBLIC_KEY=" . $k['publicKey'];
    |   > echo "VAPID_PRIVATE_KEY=" . $k['privateKey'];
    |
    | Pega los valores en tu .env. En Windows local debes habilitar
    | extension=sodium en php.ini para poder generar las llaves.
    */
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', 'mailto:admin@lavadofacil.com'),
        'public_key' => env('VAPID_PUBLIC_KEY', ''),
        'private_key' => env('VAPID_PRIVATE_KEY', ''),
    ],
];
