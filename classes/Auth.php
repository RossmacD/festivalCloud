<?php

namespace FestivalCloud;

require_once 'Connection.php';

require_once 'User.php';

require_once __DIR__.'/../vendor/autoload.php';

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'../');
// $dotenv->load();

class Auth
{
    private $cookie_name;
    private $region;
    private $version;
    private $key;
    private $secret;
    //Authenticate with AWS Acess Key and Secret
    private $client;

    private $user;

    public function __construct()
    {
        // Attempt to authenticate on creation based on authentication cookie
        try {
            $this->loadEnv();
            $this->client = new CognitoIdentityProviderClient([
                'version' => $this->version,
                'region' => $this->region,
                'credentials' => [
                    'key' => $this->key,
                    'secret' => $this->secret,
                ],
            ]);
            $awsUser = $this->client->getUser([
                'AccessToken' => $this->getAuthenticationCookie(),
            ]);
            $this->user = new User($awsUser);
        } catch (\Exception  $e) {
            // an exception indicates the accesstoken is incorrect - $this->user will still be null
        }
    }

    public function recieveAwsCallback(string $token): void
    {
        try {
            $this->loadEnv();
            // Set the user to be the returned value from AWS
            $awsUser = $this->client->getUser([
                'AccessToken' => $token,
            ]);
            $this->user = new User($awsUser);

            $this->setAuthenticationCookie($token); ?>
                <script>
                    window.location.href = 'http://localhost/festivalCloud-scenario-1/views/profile/index.php';
                </script>
                <?php
        } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {
            echo 'FAILED TO VALIDATE THE ACCESS TOKEN. ERROR = '.$e->getMessage();
        } catch (\Aws\Exception\CredentialsException $e) {
            echo 'FAILED TO AUTHENTICATE AWS KEY AND SECRET. ERROR = '.$e->getMessage();
        }
    }

    public function logout(): void
    {
        if (isset($_COOKIE[$this->cookie_name])) {
            unset($_COOKIE[$this->cookie_name]);
            setcookie($this->cookie_name, '', time() - 3600);
        }
    }

    public function isAuthenticated(): bool
    {
        return null !== $this->user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    private function loadEnv()
    {
        $this->cookie_name = $_ENV['COOKIE_NAME'];
        $this->region = $_ENV['AWS_REGION'];
        $this->version = $_ENV['AWS_VERSION'];
        $this->key = $_ENV['AWS_KEY'];
        $this->secret = $_ENV['AWS_SECRET'];
    }

    private function setAuthenticationCookie(string $accessToken): void
    {
        setcookie($this->cookie_name, $accessToken, time() + 3600);
    }

    private function getAuthenticationCookie(): string
    {
        return $_COOKIE[$this->cookie_name] ?? '';
    }
}
