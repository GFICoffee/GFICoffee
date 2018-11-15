<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AuthService
{
    /** @var \League\OAuth2\Client\Provider\GenericProvider */
    private $oauthClient;

    /** @var string */
    private $code;

    /** @var mixed */
    private $userInfo;

    /** @var mixed */
    private $oauthParameters;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->oauthParameters = $parameterBag->get('oauth');
        $this->oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $this->oauthParameters['appID'],
            'clientSecret'            => $this->oauthParameters['appPassword'],
            'redirectUri'             => $this->oauthParameters['redirectURI'],
            'urlAuthorize'            => $this->oauthParameters['authority'] . $this->oauthParameters['authorizeEndpoint'],
            'urlAccessToken'          => $this->oauthParameters['authority'] . $this->oauthParameters['tokenEndpoint'],
            'urlResourceOwnerDetails' => '',
            'scopes'                  => $this->oauthParameters['scopes'],
        ]);
    }

    public function getAuthorizationUrl() {
        return $this->oauthClient->getAuthorizationUrl();
    }

    public function setCode(string $code) {
        $this->code = $code;
    }

    public function getAccessToken() {
        return $this->oauthClient->getAccessToken('authorization_code', [
            'code' => $this->code
        ]);
    }

    public function getUserInfo(bool $force = false) {
        if ($force || !$this->userInfo) {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $this->oauthParameters['userinfoEndpoint'], array(
                'headers' => array(
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getAccessToken()->getToken()
                )
            ));
            if ($res->getStatusCode() >= 300) {
                throw new \Exception('La récupération des infos utilisateurs via oauth a échouée avec un code ' . $res->getStatusCode());
            }
            $this->userInfo = json_decode($res->getBody()->getContents(), true);
        }
        return $this->userInfo;
    }
}