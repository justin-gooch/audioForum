<?php

require "header.php";

require "databaseConnector.php";

//upload new thread. 
echo "
<form action = 'uploadFile.php' method = 'post' enctype='multipart/form-data' width = '30%' align='center'>
Select Audio File to upload:
<input type='file' name = 'uploadFile' id='uploadFile'><br>
Title <input type='text' name = 'title'><br>
<input type='submit' value='Upload File' name = 'submit'><br>
<input type='number' name='threadId' value='0' readonly class='hideMe'>
</form>
";

//get errything for list of recent threads. 
$sql = "SELECT b.postId, b.opId, b.postTime, b.postText, b.fileLink, b.userSubmitted, a.opId, a.lastReplyTime FROM postList b, listOfThreads a WHERE a.opId = b.postId ORDER BY a.lastReplyTime DESC LIMIT 30;";
//declare arrays for use to make page look nice
$postIdArray = array();
$opIdArray = array();
$postTimeArray = array();
$postTextArray = array();
$fileLinkArray = array();
$userSubmittedArray = array();
$lastReplyTimeArray = array();
	
	echo "
	<table style='width:30%' cellpadding='10'>
		<tr>
			<th>Post Id</th>
			<th>Post Time</th>
			<th>Audio</th>
			<th>Author</th>
			<th>Title</th>
			<th>Last Updated</th>
		</tr>
";
//run sql and put errything into arrays.
foreach($db->query($sql) as $row)
{
	array_push($postIdArray,  $row["postId"]);
	array_push($opIdArray,  $row['opId']);
	array_push($postTimeArray,  $row['postTime']);
	array_push($postTextArray,  $row['postText']);
	array_push($fileLinkArray,  $row['fileLink']);
	array_push($userSubmittedArray,  $row['userSubmitted']);
	array_push($lastReplyTimeArray, $row['lastReplyTime']);

	echo "<tr><th>"; 

	echo "<a href='http://quantumicar.us/audioForum/threadViewer.php?opId=" . $row['postId'] . "'>" . $row['postId'] . "</a>";
	echo "</th><th>";
	echo $row['postTime'];
	echo "</th><th>";
	echo "<audio controls><source src='http://quantumicar.us" . $row['fileLink'] . "' type='audio/wav'></audio></th><th>";
	echo $row['userSubmitted'];
	echo "</th><th>";
	echo $row['postText'];
	echo "</th><th>";
	echo $row['lastReplyTime'];
	echo "</th></tr>";
}

echo "</table>";




require "footer.php";
?>