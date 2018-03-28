<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// yields $pdo database connection
require 'connect.php';

// Create an importer that imports a list of post files (examples are provided in the data folder) into the database.
// Create a tool that given a post id from the database, renders the post content (title, body, author) as an HTML document.
// Create a tool that renders all available posts in reverse chronological order as an 