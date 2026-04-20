# Content Api

### Publish Customization

```
php artisan vendor:publish --provider=Edzhub\ContentSdk\ContentSdkServiceProvider
```

### Please place this in env file

```
ZSL_CONTENT_MANAGER_TOKEN=<yourtoken>
```

if you have admin access to create users

```
ZSL_CONTENT_ADMIN_TOKEN=<youradmintoken>
```

if you have whitelisted domain

```
ZSL_CONTENT_URL=<yourwhitelisteddomain>/api
```

### Usage

```php
use Edzhub\ContentSdk\ContentSdk;

$client = new ContentSdk();

// Set the token if not set in the environment
$client->setToken('token from db');

// Create a User
$user = $client->createUser(name: $name,email: $email,password: $password);

// Get Sub Users
$subUsers = $client->getSubUsers();

// Create a Sub User
$subUser = $client->createSubUser(userName: $userName);

// Get Classes
$classes = $client->getClasses(subUserId: $subUserId|null);

// Get Subjects
$subjects = $client->getSubjects(class_id: $classId);

// Get Chapters
$chapters = $client->getChapters(class_id: $classId,subject_id: $subjectId);

// Get Topics
$topics = $client->getTopics(chapter_id: $chapterId, language_id: $languageId|null);

// Get Activities
$activities = $client->getActivities(topic_id: $topicId);

// Get Questions.
$questions = $client->getQuestions(activity_id: $activity_id, language_id: languageId|null, per_page: 10);

// Get Signed URL for an activity
$signedUrl = $client->getSignedUrl(activity_id: $activityId);

// Get Signed URL for an chapter references
$signedUrl = $client->getSignedUrlForChapterReference(chapter_id: $chapter_id);

// Get Signed URL for description.
$signedUrl = $client->getDescriptionSignedLink(description_id: $description_id);

// Get Signed URL for instruction.
$signedUrl = $client->getInstructionSignedLink(instructor_id: $instructor_id);

// Get Signed URL for observation.
$signedUrl = $client->getObservationSignedLink(observation_id: $observation_id);

// Get Quiz Result.
$result = $client->attemptQuiz(subUserId: $subUserId, classId: $classId, activityId:$activityId, answers: $answers)

// Get Tickets.
$tickets = $client->getTickets(classId: $classId, subUserId: $subUserid)

// Unlock Activity.
$result = $client->unlockActivity(subUserId: $subUserId, activityId: $activityId)

// Deletes User.
$result = $client->deleteUser(string $id);

// Delete Sub-User.
$result = $client->deleteSubUser(string $id);
```
