<?php

require_once __DIR__ . '/../vendor/autoload.php';

class GoogleAuthService
{
    private Google_Client $client;

    public function __construct(array $config)
    {
        $this->client = new Google_Client();
        $this->client->setClientId($config['client_id']);
        $this->client->setClientSecret($config['client_secret']);
        $this->client->setRedirectUri($config['redirect_uri']);

        foreach ($config['scopes'] as $scope) {
            $this->client->addScope($scope);
        }
    }

    public function getClient(): Google_Client
    {
        return $this->client;
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function fetchAccessToken(string $code): array
    {
        return $this->client->fetchAccessTokenWithAuthCode($code);
    }

    public function getUserProfile(string $accessToken): array
    {
        $this->client->setAccessToken($accessToken);

        $googleService = new Google_Service_Oauth2($this->client);
        $profile = $googleService->userinfo->get();

        return [
            'email' => $profile['email'],
            'name' => $profile['name'],
        ];
    }
}
