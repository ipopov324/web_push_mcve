<?php

declare(strict_types=1);

//namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Namshi\Cuzzle\Middleware\CurlFormatterMiddleware;

class DebugFactory extends Factory
{
    protected $logsHandler;

    public function withDebugging(TestHandler $handler): self
    {
        $factory = clone $this;
        $factory->logsHandler = $handler;

        return $factory;
    }

    public function createApiClient(array $config = null, array $additionalScopes = null): Client
    {
        $config = $config ?? [];
        $additionalScopes = $additionalScopes ?? [];

        $googleAuthTokenMiddleware = $this->createGoogleAuthTokenMiddleware($additionalScopes);

        $stack = HandlerStack::create();
        foreach ($this->httpClientMiddlewares as $middleware) {
            $stack->push($middleware);
        }
        $stack->push($googleAuthTokenMiddleware);

        $this->enableDebugging($stack);

        $config = \array_merge(
            $this->httpClientConfig,
            $config ?? [],
            [
                'handler' => $stack,
                'auth' => 'google_auth',
            ]
        );

        return new Client($config);
    }

    private function enableDebugging(HandlerStack $stack)
    {
        if($this->logsHandler !== null) {
            $logger = new Logger('guzzle.to.curl'); //initialize the logger
            $logger->pushHandler($this->logsHandler);

            $stack->after('cookies', new CurlFormatterMiddleware($logger)); //add the cURL formatter middleware
        }

    }
}
