<?php
namespace Vertisan\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use Vertisan\OAuth2\Client\Provider\Exception\TwitchHelixIdentityProviderException;

class TwitchHelix extends AbstractProvider
{
    use BearerAuthorizationTrait;

    const PATH_AUTHORIZE = '/oauth2/authorize';
    const PATH_TOKEN = '/oauth2/token';
    const USER_RESOURCE = '/helix/users';
    const SCOPE_SEPARATOR = ' ';

    protected $domain = 'https://id.twitch.tv';
    protected $resourceDomain = 'https://api.twitch.tv';

    private $scopes = ['user:read:email'];
    private $responseError = 'error';
    private $responseCode;

    public function __construct(array $options = [])
    {
        $possible = $this->getConfigurableOptions();
        $configured = array_intersect_key($options, array_flip($possible));

        foreach ($configured as $key => $value) {
            $this->$key = $value;
        }

        $options = array_diff_key($options, $configured);

        parent::__construct($options);
    }

    protected function getConfigurableOptions(): array
    {
        return [
            'accessTokenMethod',
            'accessTokenResourceOwnerId',
            'scopeSeparator',
            'responseError',
            'responseCode',
            'responseResourceOwnerId',
            'scopes',
        ];
    }

    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . self::PATH_AUTHORIZE;
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->domain . self::PATH_TOKEN;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->resourceDomain . self::USER_RESOURCE;
    }

    public function getDefaultScopes(): array
    {
        return $this->scopes;
    }

    protected function getScopeSeparator(): string
    {
        return self::SCOPE_SEPARATOR;
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data[$this->responseError])) {
            $error = $data[$this->responseError];
            $code  = $this->responseCode ? $data[$this->responseCode] : 0;

            throw new TwitchHelixIdentityProviderException($error, $code, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): TwitchHelixResourceOwner
    {
        return new TwitchHelixResourceOwner($response);
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Client-ID' => $this->clientId
        ];
    }

    protected function getAuthorizationHeaders($token = null): array
    {
        if ($token === null) {
            return [];
        }

        return [
            'Authorization' => 'Bearer '. $token
        ];
    }
}
