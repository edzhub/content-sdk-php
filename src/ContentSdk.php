<?php

namespace Edzhub\ContentSdk;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ContentSdk
{
    private string $accessToken;

    public function __construct(?string $token = null, private string $version = 'v1')
    {
        $this->accessToken = $token ?? \config('zsl-content.MANAGER_TOKEN');
    }

    /**
     * Get the list of States.
     *
     * @throws ConnectionException
     */
    public function getStates(): PromiseInterface|Response
    {
        $token = config('zsl-content.ADMIN_TOKEN');

        return Http::withToken($token)->acceptJson()->post($this->getUrl(path: 'state'));
    }

    /**
     * Get the list of All Classes.
     *
     * @throws ConnectionException
     */
    public function getAllClasses(string $syllabus, string $stateId = ''): PromiseInterface|Response
    {
        $token = config('zsl-content.ADMIN_TOKEN');

        return Http::withToken($token)->acceptJson()->get($this->getUrl(path: 'class'), ['syllabus' => $syllabus, 'state_id' => $stateId]);
    }

    /**
     * Create a new user.
     *
     * @throws ConnectionException
     */
    public function createUser(string $name, string $email, string $passowrd, array $classes): PromiseInterface|Response
    {
        $token = config('zsl-content.ADMIN_TOKEN');

        return Http::withToken($token)->acceptJson()->post($this->getUrl(path: 'user/sdk/create'), ['name' => $name, 'email' => $email, 'password' => $passowrd, 'classes' => $classes]);
    }

    /**
     * Get the list of sub users.
     *
     * @throws ConnectionException
     */
    public function getSubUsers(): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'user/subUser'));
    }

    /**
     * Create a new sub user.
     *
     * @throws ConnectionException
     */
    public function createSubUser($userName): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: 'user/subUser'), ['user_name' => $userName]);
    }

    /**
     * Get the list of Classes.
     *
     * @throws ConnectionException
     */
    public function getClasses(): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'class'));
    }

    /**
     * Assign a class to a sub user.
     *
     *
     * @throws ConnectionException
     */
    public function assignClass(string $classId, string $subUserId): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: 'class/sub-user/add'), ['class_id' => $classId, 'sub_user_id' => $subUserId]);
    }

    /**
     * Remove a class from a sub user.
     *
     * @throws ConnectionException
     */
    public function removeClass(string $classId, string $subUserId): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: 'class/sub-user/remove'), ['class_id' => $classId, 'sub_user_id' => $subUserId]);
    }

    /**
     * Get the list of Subjects for a given class.
     *
     * @throws ConnectionException
     */
    public function getSubjects($class_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'subject'), ['class_id' => $class_id]);
    }

    /**
     * Get the list of Chapters for a given class and subject.
     *
     * @throws ConnectionException
     */
    public function getChapters($class_id, $subject_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'chapter'), [
            'class_id' => $class_id,
            'subject_id' => $subject_id,
        ]);
    }

    /**
     * Get the list of Topics for a given chapter.
     *
     * @throws ConnectionException
     */
    public function getTopics($chapter_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'topic'), ['chapter_id' => $chapter_id]);
    }

    /**
     * Get the list of Activities for a given topic.
     *
     * @throws ConnectionException
     */
    public function getActivities($topic_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'activity'), ['topic_id' => $topic_id]);
    }

    /**
     * Get Signed URL for a given activity.
     *
     * @throws ConnectionException
     */
    public function getSignedUrl($activity_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "activity/signed-url/{$activity_id}"));
    }

    /**
     * Set the access token for the SDK.
     */
    public function setToken(string $token): void
    {
        $this->accessToken = $token;
    }

    /**
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
            $url .= $path;
        }

        return $url;
    }
}
