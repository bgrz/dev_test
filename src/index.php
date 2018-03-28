<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// yields $pdo database connection
require 'connect.php';

// Create an importer that imports a list of post files (examples are provided in the data folder) into the database.
echo "\n\n----Task 1 Follows----\n\n";

$query = [
    'exists' => 'SELECT COUNT(*) FROM posts WHERE id = :id LIMIT 1',
    'insert' => 'INSERT INTO posts SET id = :id, title = :title, body = :body, created_at = :created_at, modified_at = :modified_at, author = :author',
];

foreach(glob("data/*.json") AS $filename) {
    $data = file_get_contents($filename);

    // decode data to 1) prepare for insertion to DB, 2) loosely determine if it's valid JSON
    $result = json_decode($data, TRUE);

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

echo "\n\n----Task 2 Passed----\n\n";

// Render all available posts in reverse chronological order as an HTML document. Include the post title and author in the rendered document.

echo "\n\n----Task 3 Follows----\n\n";

echo "\n\n----Task 3 Passed----\n\n";