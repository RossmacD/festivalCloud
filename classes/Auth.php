<?php

namespace FestivalCloud;

require_once 'Connection.php';

require_once 'User.php';

require_once __DIR__.'/../vendor/autoload.php';

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class Auth
{
    private const COOKIE_NAME = 'festival-cloud-access-token';
    private $region = 'us-east-1';
    private $version = '2016-04-18';
    private $key = 'ASIA32ONXAD2BDSL74MM';
    private $secret = 'I0yKoLSISIXDudkVIUQMILvLt4Zzlsl4RlJ1VZ';
    //Authenticate with AWS Acess Key and Secret
    private $client;

    private $user;

    public function __construct()
    {
        // Attempt to authenticate on creation based on authentication cookie
        try {
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
        if (isset($_COOKIE[self::COOKIE_NAME])) {
            unset($_COOKIE[self::COOKIE_NAME]);
            setcookie(self::COOKIE_NAME, '', time() - 3600);
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

    // public function getEmail(): string
    // {
    //     if ($this->isAuthenticated()) {
    //         foreach($this->user["UserAttributes"] as $key=>$val){

    //         }
    //         return '';
    //     }

    //     return '';
    // }

    // private function getAttribute(string $attribute){
    //     return $this->user['Attributes'][$attribute]
    // }

    private function setAuthenticationCookie(string $accessToken): void
    {
        setcookie(self::COOKIE_NAME, $accessToken, time() + 3600);
    }

    private function getAuthenticationCookie(): string
    {
        return $_COOKIE[self::COOKIE_NAME] ?? '';
    }
}
