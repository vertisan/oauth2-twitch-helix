<?php
namespace Vertisan\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class TwitchHelixResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response)
    {
        $this->response = $response['data'][0];
    }

    /**
     * User’s ID.
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->getValueByKey($this->response, 'id');
    }

    /**
     * User’s login name.
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->getValueByKey($this->response, 'login');
    }

    /**
     * User’s display name.
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->getValueByKey($this->response, 'display_name');
    }

    /**
     * User’s broadcaster type: "partner", "affiliate", or "".
     *
     * @return string
     */
    public function getBroadcasterType(): string
    {
        return $this->getValueByKey($this->response, 'broadcaster_type');
    }

    /**
     * User’s channel description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getValueByKey($this->response, 'description');
    }

    /**
     * URL of the user’s profile image.
     *
     * @return string
     */
    public function getProfileImageUrl(): string
    {
        return $this->getValueByKey($this->response, 'profile_image_url');
    }

    /**
     * URL of the user’s offline image.
     *
     * @return string
     */
    public function getOfflineImageUrl(): string
    {
        return $this->getValueByKey($this->response, 'offline_image_url');
    }

    /**
     * Total number of views of the user’s channel.
     *
     * @return int
     */
    public function getViewCount(): int
    {
        return (int) $this->getValueByKey($this->response, 'view_count');
    }

    /**
     * User’s email address. Returned if the request includes the user:read:email scope.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * User’s type: "staff", "admin", "global_mod", or "".
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValueByKey($this->response, 'type');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
