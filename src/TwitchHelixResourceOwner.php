<?php
namespace Vertisan\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class TwitchHelixResourceOwner implements ResourceOwnerInterface
{
    private $response;
    private $login;
    private $id;
    private $displayName;
    private $broadcasterType;
    private $description;
    private $profileImageUrl;
    private $offlineImageUrl;
    private $viewCount;
    private $email;
    private $type;

    public function __construct(array $response)
    {
        $this->response = $response[0];

        $this->id = $this->response['id'];
        $this->login = $this->response['login'];
        $this->displayName = $this->response['display_name'];
        $this->broadcasterType = $this->response['broadcaster_type'];
        $this->description = $this->response['description'];
        $this->profileImageUrl = $this->response['profile_image_url'];
        $this->offlineImageUrl = $this->response['offline_image_url'];
        $this->viewCount = $this->response['view_count'];
        $this->email = $this->response['email'];
        $this->type = $this->response['type'];
    }

    /**
     * User’s ID.
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * User’s login name.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * User’s display name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * User’s broadcaster type: "partner", "affiliate", or "".
     *
     * @return string
     */
    public function getBroadcasterType()
    {
        return $this->broadcasterType;
    }

    /**
     * User’s channel description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * URL of the user’s profile image.
     *
     * @return string
     */
    public function getProfileImageUrl()
    {
        return $this->profileImageUrl;
    }

    /**
     * URL of the user’s offline image.
     *
     * @return string
     */
    public function getOfflineImageUrl()
    {
        return $this->offlineImageUrl;
    }

    /**
     * Total number of views of the user’s channel.
     *
     * @return int
     */
    public function getViewCount()
    {
        return (int) $this->viewCount;
    }

    /**
     * User’s email address. Returned if the request includes the user:read:email scope.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * User’s type: "staff", "admin", "global_mod", or "".
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}