<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

require __DIR__ . '/vendor/autoload.php';

$serviceAccount =
    (new Kreait\Firebase\ServiceAccount())
        ->withClientId(getenv('CLIENT_ID'))
        ->withPrivateKey(getenv('PRIVATE_KEY'))
        ->withProjectId(getenv('PROJECT_ID'))
        ->withClientEmail(getenv('CLIENT_EMAIL'));

$factory = (new Factory())->withServiceAccount($serviceAccount);

$messaging = $factory->create()->getMessaging();

$notification = [
    'title' => 'Your withdrawal has been completed"',
    'body' => "Check your Transaction History in our app for more details.",
];

$rawTokens = file_get_contents("tokens.json");
$tokens = json_decode($rawTokens, true);

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
    } catch (\Throwable $e) {
        echo $e->getCode() . $e->getMessage() ; //. ' token: '. $token;
    }
}
