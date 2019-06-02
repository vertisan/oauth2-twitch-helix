# Twitch Helix Provider for OAuth 2.0 Client

[![Build Status](https://travis-ci.com/vertisan/oauth2-twitch-helix.svg?branch=master)](https://travis-ci.com/vertisan/oauth2-twitch-helix) 
[![Latest Stable Version](https://poser.pugx.org/vertisan/oauth2-twitch-helix/v/stable)](https://packagist.org/packages/vertisan/oauth2-twitch-helix) 
[![License](https://poser.pugx.org/vertisan/oauth2-twitch-helix/license)](https://packagist.org/packages/vertisan/oauth2-twitch-helix) 

This package provides Twitch (new version Helix) OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require vertisan/oauth2-twitch-helix
```

## Usage

Usage is the same as The League's OAuth client, using `Vertisan\OAuth2\Client\Provider\TwitchHelix` as the provider.

```php
$provider = new \Vertisan\OAuth2\Client\Provider\TwitchHelix([
    'clientId' => "YOUR_CLIENT_ID",
    'clientSecret' => "YOUR_CLIENT_SECRET",
    'redirectUri' => "http://your-redirect-uri-passed-in-twitch-dashboard"
]);
```
You can also optionally add a `scopes` key to the array passed to the constructor. The available scopes are documented
on the [New Twitch API Reference](https://dev.twitch.tv/docs/api/reference/).

Testing
---------
```bash
$ ./vendor/bin/phpunit
```