<?php

namespace Zsl\ContentSdk;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ContentSdk
{
    private readonly string $subUsers;

    private string $accessToken;

    public function __construct(?string $token = null, private string $version = 'v1')
    {
        $this->accessToken = $token ?? \config('zsl-content.MANAGER_TOKEN');
        $this->subUsers = 'user/subuser';
    }

    public function getUsers(): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: $this->subUsers));
    }

    public function createUser($userName): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: $this->subUsers), ['user_name' => $userName]);
    }

    public function setToken(string $token): void
    {
        $this->accessToken = $token;
    }

    private function getUrl(string $path = ''): string
    {
        $url = config('zsl-content.CONTENT_URL');
        if (! str_ends_with($url, '/')) {
            $url .= '/';
        }
        $url .= $this->version.'/';
        if ($path !== '') {
            $url = $path;
        }

        return $url;
    }
}
