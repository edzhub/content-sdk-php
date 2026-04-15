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

        return Http::withToken($token)->acceptJson()->get($this->getUrl(path: 'state'));
    }

    /**
     * Get the list of Languages
     *
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getLanguages(): PromiseInterface|Response
    {
        $token = config('zsl-content.ADMIN_TOKEN');
        return Http::withToken($token)->acceptJson()->get($this->getUrl(path: 'language'));
    }

    /**
     * Get the list of All Classes.
     *
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getAllClasses(?string $syllabus = null, ?string $stateId = null): PromiseInterface|Response
    {
        $token = config('zsl-content.ADMIN_TOKEN');

        return Http::withToken($token)->acceptJson()->get($this->getUrl(path: 'class'), ['syllabus' => $syllabus, 'state_id' => $stateId]);
    }

    /**
     * Create a new user.
     *
     * @return PromiseInterface|Response
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
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getSubUsers(): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'user/subUser'));
    }

    /**
     * Create a new sub user.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function createSubUser($userName): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: 'user/subUser'), ['user_name' => $userName]);
    }

    /**
     * Get the list of Classes.
     *
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getClasses(): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'user/assigned-classes'));
    }

    /**
     * Assign a class to a sub user.
     *
     * @param string $classId The ID of the class to assign.
     * @param string $subUserId The ID of the sub user to assign the class to.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function assignClass(string $classId, string $subUserId): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: 'class/sub-user/add'), ['class_id' => $classId, 'sub_user_id' => $subUserId]);
    }

    /**
     * Remove a class from a sub user.
     * 
     * @param string $classId The ID of the class to remove.
     * @param string $subUserId The ID of the sub user to remove the class from.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function removeClass(string $classId, string $subUserId): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: 'class/sub-user/remove'), ['class_id' => $classId, 'sub_user_id' => $subUserId]);
    }

    /**
     * Get the list of Subjects for a given class.
     *
     * @param string $class_id The ID of the class to get the subjects for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getSubjects($class_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'subject'), ['class_id' => $class_id]);
    }

    /**
     * Get the list of Chapters for a given class and subject.
     *
     * @param string $class_id The ID of the class to get the chapters for.
     * @param string $subject_id The ID of the subject to get the chapters for.
     * 
     * @return PromiseInterface|Response
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
     * @param string $chapter_id The ID of the chapter to get the topics for.
     * @param string|null $language_id The ID of the language to get the topics for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getTopics($chapter_id, $language_id = null): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'topic'), ['chapter_id' => $chapter_id, 'language_id' => $language_id]);
    }

    /**
     * Get the list of Activities for a given topic.
     *
     * @param string $topic_id The ID of the topic to get the activities for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getActivities($topic_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: 'activity'), ['topic_id' => $topic_id]);
    }

    public function getQuestions($activity_id, $language_id = null, $per_page = 10): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "activity/question-bank"), [
            'activity_id' => $activity_id,
            "language_id" => $language_id,
            "per_page" => $per_page,
        ]);
    }

    /**
     * Get Signed URL for a given activity.
     *
     * @param string $activity_id The ID of the activity to get the signed URL for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getSignedUrl($activity_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "activity/signed-url/{$activity_id}"));
    }

    /**
     * Get Signed URL for a given chapter.
     *
     * @param string $chapter_id The ID of the chapter to get the signed URL for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getSignedUrlForChapterReference($chapter_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "chapter/signed-url/{$chapter_id}"));
    }


    /**
     * Get Signed URL for a given description.
     *
     * @param string $description_id The ID of the description to get the signed URL for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getDescriptionSignedLink(string $description_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "activity/description/signed-url/{$description_id}"));
    }

    /**
     * Get Signed URL for a given instruction.
     *
     * @param string $instruction_id The ID of the instruction to get the signed URL for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getInstructionSignedLink(string $instruction_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "activity/instruction/signed-url/{$instruction_id}"));
    }

    /**
     * Get Signed URL for a given observation.
     *
     * @param string $observation_id The ID of the observation to get the signed URL for.
     * 
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getObservationSignedLink(string $observation_id): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->get($this->getUrl(path: "activity/observation/signed-url/{$observation_id}"));
    }

    public function getQuizResult(string $subUserId, string $classId, string $activityId, array $answers): PromiseInterface|Response
    {
        return Http::withToken($this->accessToken)->acceptJson()->post($this->getUrl(path: "activity/question-bank/attempt-quiz"), [
            'sub_user_id' => $subUserId,
            'classes_id' => $classId,
            'activity_id' => $activityId,
            'answers' => $answers,
        ]);
    }

    /**
     * Set the access token for the SDK.
     * @param string $token The access token to set.
     * @return void
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
        $url .= $this->version . '/';
        if ($path !== '') {
            $url .= $path;
        }

        return $url;
    }
}
