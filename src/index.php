<?php
/**
 * silverorange developer test
 * Bogdan - bgdnrz@gmail.com
 *
 * 3 tasks illustrating PHP and MySQL capabilities
 */

// yields $pdo database connection
require 'connect.php';

// Import a list of post files (examples are provided in the data folder) into the database.
echo "\n\n----Task 1 Follows----\n\n";

$query = [
    'exists' => '
    SELECT COUNT(*) 
    FROM posts WHERE id = :id 
    LIMIT 1',
    'insert' => '
    INSERT INTO posts 
    SET id = :id, title = :title, body = :body, created_at = :created_at, modified_at = :modified_at, author = :author',
];

foreach (glob("data/*.json") AS $filename) {
    $data = file_get_contents($filename);

    // decode data to 1) prepare for insertion to DB, 2) loosely determine if it's valid JSON
    $result = json_decode($data, true);

    // loosely confirm and proceed if this is valid JSON by checking last decode error for presence of none
    if (json_last_error() === JSON_ERROR_NONE) {
        // confirm that record is not yet in database
        $stmt = $pdo->prepare($query['exists']);
        $stmt->execute(['id' => $result['id']]);
        $exists = $stmt->fetch();

        // if record is not in database, insert
        if (!$exists) {
            $stmt = $pdo->prepare($query['insert']);
            $stmt->execute([$result['id'], $result['title'], $result['body'], $result['created_at'], $result['modified_at'], $result['author']]);
        }
    }
}

echo "\n\n----Task 1 Passed----\n\n";

// Given a post id from the database, renders the post content (title, body, author) as an HTML document.

echo "\n\n----Task 2 Follows----\n\n";

$query = '
    SELECT title, body, author 
    FROM posts WHERE id = :id';

$id = '2f1fe9c0-bdbf-4104-bee2-3c0ec514436f';
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$data = $stmt->fetch();

if ($data) {
    echo <<<EOT
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>silverorange - task 2 output</title>
  </head>
  <body>
    <p>title: {$data['title']}</p>
    <p>body: {$data['body']}</p>
    <p>author: {$data['author']}</p>
  </body>
</html>
EOT;
}

echo "\n\n----Task 2 Passed----\n\n";

// Render all available posts in reverse chronological order as an HTML document. 
// Include the post title and author in the rendered document.

echo "\n\n----Task 3 Follows----\n\n";

$result_data = '';

$query = '
    SELECT authors.full_name, posts.title 
    FROM authors, posts 
    WHERE authors.id=posts.author 
    ORDER BY posts.created_at DESC';

$stmt = $pdo->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll();

if ($data) {
    foreach ($data AS $index => $result) {
        $result_data .= '
    <p>author: ' . $result['full_name'] . '</p>
    <p>title: ' . $result['title'] .'</p>
';
    }

       echo <<<EOT
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>silverorange - task 3 output</title>
  </head>
  <body>
    $result_data
  </body>
</html>
EOT;
}

echo "\n\n----Task 3 Passed----\n\n";
