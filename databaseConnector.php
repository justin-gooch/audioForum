<?php

//php database connection file
//only include on pages you need to use to access the database

//establish pdo connection

$db = new PDO('mysql:host=localhost;dbname=dbNameGoesHere', 'dbUserNameGoesHere', 'dbPasswordGoesHere');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


?>
