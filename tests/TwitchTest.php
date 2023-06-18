<?php
namespace Vertisan\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Vertisan\OAuth2\Client\Provider\TwitchHelix;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;

class TwitchTest extends TestCase
{
    use QueryBuilderTrait;

    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new TwitchHelix([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none'
        ]);
    }

    public function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testScopes()
    {
        $options = ['scope' => [uniqid('', true), uniqid('', true)]];
        $query = ['scope' => implode(TwitchHelix::SCOPE_SEPARATOR, $options['scope'])];
        $url = $this->provider->getAuthorizationUrl($options);
        $this->assertStringContainsString($this->buildQueryString($query), $url);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals(TwitchHelix::PATH_AUTHORIZE, $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals(TwitchHelix::PATH_TOKEN, $uri['path']);
    }

    public function testGetResourceOwnerDetailsUrl()
    {
        $token = new AccessToken(['access_token' => 'mock_access_token']);
        $url = $this->provider->getResourceOwnerDetailsUrl($token);
        $uri = parse_url($url);
        $this->assertEquals(TwitchHelix::USER_RESOURCE, $uri['path']);
    }

    /**
     * @throws IdentityProviderException
     */
    public function testGetAccessToken()
    {
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')
            ->andReturn(json_encode([
                'access_token' => 'mock_access_token',
                'token_type' => 'bearer',
                'expires_in' => 1000,
                'refresh_token' => 'mock_refresh_token',
            ]))
        ;

        $response->shouldReceive('getHeader')
            ->andReturn(['content-type' => 'json']);

        $response->shouldReceive('getStatusCode')
            ->andReturn(200);

        $client = m::mock(ClientInterface::class);

        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($response);

        $this->provider
            ->setHttpClient($client);

        $token = $this->provider
            ->getAccessToken('authorization_code', [
                'code' => 'mock_authorization_code'
            ])
        ;

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertLessThanOrEqual(time() + 1000, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }
}