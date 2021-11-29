<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;


require __DIR__ . '/vendor/autoload.php';

echo 'tst';

$serviceAccount =
    (new Kreait\Firebase\ServiceAccount())
        ->withClientId(getenv('CLIENT_ID'))
        ->withPrivateKey(getenv('PRIVATE_KEY'))
//        ->withPrivateKey("")
        ->withProjectId(getenv('PROJECT_ID'))
        ->withClientEmail(getenv('CLIENT_EMAIL'));

$factory = (new Factory())->withServiceAccount($serviceAccount);

$messaging = $factory->create()->getMessaging();

$notification = [
    'title' => 'test_subject',
    'body' => 'test body',
];

$tokens = [

];

foreach ($tokens as $token) {
    $message = CloudMessage::fromArray([
        'webpush' => [
            'notification' => $notification,
        ],
        'notification' => $notification,
        'headers' => ['TTL' => 84600],
        'token' => $token,
        'data' => ['require_interaction' => 'true'],
    ]);

    try {
        $response = $messaging->send($message);
        var_dump($response);
    }catch (\Throwable $e) {
        echo $e->getMessage(). ' token: '.$token;
    }
}
