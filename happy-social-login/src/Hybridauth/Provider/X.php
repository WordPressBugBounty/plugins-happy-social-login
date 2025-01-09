<?php

namespace HappySocialLogin\Hybridauth\Provider;

use AdrienGras\PKCE\PKCEUtils;
use Hybridauth\Adapter\OAuth2;
use Hybridauth\Exception\InvalidAuthorizationStateException;
use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\Data;
use Hybridauth\User;

/*
 * X OAuth2 provider adapter.
 */
class X extends OAuth2 {

    /**
     * {@inheritdoc}
     */
    // phpcs:ignore
    protected $scope = 'tweet.read users.read offline.access';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://api.twitter.com/2/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://twitter.com/i/oauth2/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://api.twitter.com/2/oauth2/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://developer.twitter.com/en/docs/authentication/oauth-2-0';

    /**
     * {@inheritdoc}
     */
    protected function getAuthorizeUrl($parameters = [])
    {
        $PKCE = PKCEUtils::generateCodePair();
        $code_verifier = $PKCE['code_verifier'];
        $code_challenge = $PKCE['code_challenge'];

        $this->storeData('code_verifier', $code_verifier);

        $this->AuthorizeUrlParameters += [
            'code_challenge' => $code_challenge,
            'code_challenge_method' => 'S256'
        ];

        return parent::getAuthorizeUrl($parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function exchangeCodeForAccessToken($code){
        $code_verifier = $this->getStoredData('code_verifier');

        $this->tokenExchangeParameters += [
            'code_verifier' => $code_verifier
        ];

        $this->deleteStoredData('code_verifier');

        $this->tokenExchangeHeaders = [
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
        ];

        return parent::exchangeCodeForAccessToken($code);
    }

    /**
     * {@inheritdoc}
     *
     * See: https://developer.twitter.com/en/docs/twitter-api/users/lookup/api-reference/get-users-me
     */
    public function getUserProfile()
    {
        $fields = [
            'id',
            'name',
            'description',
            'url',
            'location',
            'profile_image_url',
            'public_metrics'
        ];

        $response = $this->apiRequest('users/me', 'GET', ['user.fields' => implode(',', $fields)]);

        $data = new Data\Collection($response);

        if (!$data->exists('data')) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $data = $data->filter('data');

        $userProfile = new User\Profile();

        $full_name = explode(' ', $data->get('name'));
        if (count($full_name) < 2) {
            $full_name[1] = '';
        }

        $userProfile->identifier = $data->get('id');
        $userProfile->displayName = $data->get('username');
        $userProfile->description = $data->get('description');
        $userProfile->firstName = $full_name[0];
        $userProfile->lastName = $full_name[1];
        $userProfile->email = $data->get('email');
        $userProfile->emailVerified = $data->get('email');
        $userProfile->webSiteURL = $data->get('url');
        $userProfile->region = $data->get('location');

        $userProfile->profileURL = $data->exists('username')
            ? ('https://x.com/' . $data->get('username'))
            : '';

        $photoSize = $this->config->get('photo_size') ?: 'original';
        $photoSize = $photoSize === 'original' ? '' : "_{$photoSize}";
        $userProfile->photoURL = $data->exists('profile_image_url')
            ? str_replace('_normal', $photoSize, $data->get('profile_image_url'))
            : '';

        $public_metrics = $data->filter('public_metrics');
        $userProfile->data = [
            'followed_by' => $public_metrics->get('followers_count'),
            'follows' => $public_metrics->get('following_count'),
            'like_count' => $public_metrics->get('like_count'),
            'tweet_count' => $public_metrics->get('tweet_count'),
            'listed_count' => $public_metrics->get('listed_count'),
        ];

        return $userProfile;
    }
}
