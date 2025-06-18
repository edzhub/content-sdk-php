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
$classes = $client->getClasses();

// Get Subjects
$subjects = $client->getSubjects(class_id: $classId);

// Get Chapters
$chapters = $client->getChapters(class_id: $classId,subject_id: $subjectId);

// Get Topics
$topics = $client->getTopics(chapter_id: $chapterId);

// Get Activities
$activities = $client->getActivities(topic_id: $topicId);

// Get Signed URL for an activity
$signedUrl = $client->getSignedUrl(activity_id: $activityId);
```
