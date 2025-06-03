<?php

namespace Edzhub\ContentSdk;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ContentSdk
{
    private string $accessToken;

    public function __construct(?string $token = null, private string $version = 'v1')
    {
        $this->accessToken = $token ?? \config('zsl-content.MANAGER_TOKEN');
    }

    /*
     * Get the list of users.
     */
    public function getUsers(): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'user/subUser'));
    }

    /*
     * Create a new user.
     */
    public function createUser($userName): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: 'user/subUser'), ['user_name' => $userName]);
    }

    /*
     * Get the list of Classes.
     */

    public function getClasses(): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'class'));
    }

    /*
     * Get the list of Subjects for a given class.
     */
    public function getSubjects($class_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'subject'), ['class_id' => $class_id]);
    }

    /*
     * Get the list of Chapters for a given class and subject.
     */
    public function getChapters($class_id, $subject_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'chapter'), [
            'class_id' => $class_id,
            'subject_id' => $subject_id,
        ]);
    }

    /*
     * Get the list of Topics for a given chapter.
     */
    public function getTopics($chapter_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'topic'), ['chapter_id' => $chapter_id]);
    }

    /*
     * Get the list of Activities for a given topic.
     */
    public function getActivities($topic_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'activity'), ['topic_id' => $topic_id]);
    }

    /*
     * Get Signed URL for a given activity.
     */
    public function getSignedUrl($activity_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "activity/signed-url/{$activity_id}"));
    }

    /*
     * Set the access token for the SDK.
     */
    public function setToken(string $token): void
    {
        $this->accessToken = $token;
    }

    /*
     * Get the URL for the API.
     */
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
