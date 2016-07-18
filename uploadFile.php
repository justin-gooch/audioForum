<?php

//header
include "header.php";

//connect to database (passes pdo as $db
include "databaseConnector.php";

//later put what text was submitted with the audio if any. 
$submittedPostText = $_POST['title'];

//later put something to determine the user
$userName = "Anonymous";

//0 if op; opThreadid if not
$opId = $_POST['threadId'];

//get time and put it into unix format in string
$WHATTIMEISIT = time();

$uploadDirectory = "/var/www/quantumicar.us/public_html/audioForum/uploads/";

//get temp file name / location for testing
$tempFileLocation = $_FILES['uploadFile']['tmp_name'];


//get mime type (a bug will be thrown if the filesize is greater than 2mb; this can be changed in the php ini file somewhere around line 800...)
$mimeTypeString = mime_content_type($tempFileLocation);

//get ending extension
$mimeExtension = explode("/", $mimeTypeString);

//make uploads into something playable. 
if($mimeExtension[1] == "x-wav" || $mimeExtension[1] == "vnd.wav")
{
	$mimeExtension = "wav";
}
else
{
	$mimeExtension = $mimeExtension[1];
}

//$uploadFile = $uploadDirectory . basename($_FILES['uploadFile']['name']);
$uploadFile = $uploadDirectory . $WHATTIMEISIT . "." . $mimeExtension;

$webFilePath = "/audioForum/uploads/" . $WHATTIMEISIT . "." . $mimeExtension;

//get file size
$fileSize = $_FILES['uploadFile']['size'];

//array of accepted mime types...
$mimeArray = array("audio/mpeg", "audio/mp4", "audio/ogg", "audio/vorbis", "audio/vnd.wav", "audio/x-wav", "audio/3gpp", "video/3gpp");

//first start the check to see if the file isn't too fucking big for this shit
if($fileSize <= 2000000)
{	

	//check type of file using inarray to make sure user isn't an asshole
	if(in_array($mimeTypeString, $mimeArray))
	{
		//check for upload attack and upload file
		if(move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadFile))
		{
			createOp($webFilePath, $WHATTIMEISIT, $db, $submittedPostText, $userName, $opId);
		}
		else
		{
			echo "file upload didn't work; plz re-evaluate what could have gone wrong here";
		}

	}

	else
	{
		echo "Filetype not accepted";
	}
}
else
{
	echo "Sorry; but that's too big to fit, also your comment isn't clever";
}

//footer file
include "footer.php";

/*
*Functions go here
*/
function createOp($filePath, $timeStamp, $db, $submittedPostText, $userName, $opId)
{

	//insert into postList table
	$sqlStatement = $db->prepare("INSERT INTO postList (opId, postTime, postText, fileLink, userSubmitted) VALUES (:opId, :postTime, :postText, :fileLink, :userSubmitted);");

	//set variables
	$postTime = $timeStamp;
	$postText = $submittedPostText;
	$currFilePath = $filePath;
	$userSubmitted = $userName;

	//bind parameters
	$sqlStatement->bindParam(":opId", $opId, PDO::PARAM_INT);
	$sqlStatement->bindParam(":postTime", $postTime, PDO::PARAM_INT);
	$sqlStatement->bindParam(":postText", $postText, PDO::PARAM_STR);
	$sqlStatement->bindParam(":fileLink", $currFilePath, PDO::PARAM_STR);
	$sqlStatement->bindParam(":userSubmitted", $userSubmitted, PDO::PARAM_STR);
	
	//execute sql
	$sqlStatement->execute();
	//$sqlStatement->debugDumpParams();

	
	
	if($opId == 0)
	{
		//get last given id for listOfThreads table
		$sqlStatement = $db->prepare("SELECT postId FROM postList WHERE fileLink = :fileLink;");
		$sqlStatement->bindParam(":fileLink", $currFilePath, PDO::PARAM_STR);
		$currFilePath = $filePath;
		$sqlStatement->execute();
		$opId = $sqlStatement->fetchColumn();

		//insert op into listOfThreads	
		$sqlStatement = $db->prepare("INSERT INTO listOfThreads (opId, lastReplyTime) VALUES (:opId, :lastReplyTime);");

		//set variables
		$id = (int)$opId;
		$timeStamp = (int)$timeStamp;
	
		//bind parameters
		$sqlStatement->bindParam(":opId", $id, PDO::PARAM_INT);
		$sqlStatement->bindParam(":lastReplyTime", $timeStamp, PDO::PARAM_INT);
	
		//execute and debug
		$sqlStatement->execute();
		//forward user to thread page after post
		header("location: threadViewer.php?opId=" . $opId);
		die();	
	}
	else
	{
		$lastReplyTime = $timeStamp;
		$opId = $opId;
		$sqlStatement = $db->prepare("UPDATE listOfThreads SET lastReplyTime = '" . $lastReplyTime . "' WHERE opId = '" . $opId . "';");

		$sqlStatement->execute();

		//forward user to thread page after post
		header("location: threadViewer.php?opId=" . $opId);
		die();
	}
	
}

?>
