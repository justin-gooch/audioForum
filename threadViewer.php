<?php

require 'header.php';

require 'databaseConnector.php';

$opId = $_GET['opId'];

echo "
<form action = 'uploadFile.php' method = 'post' enctype='multipart/form-data' width = '30%' align='center'>
Select Audio File to upload:
<input type='file' name = 'uploadFile' id='uploadFile'><br>
Text <input type='text' name = 'title'><br>
<input type='submit' value='Upload File' name = 'submit'><br>
<input type='number' name='threadId' value='" . $opId . "' readonly class='hideMe'>
</form>
";

//start table for posts.
	echo "
	<table style='width:30%' cellpadding='10'>
		<tr>
			<th>Post Id</th>
			<th>Post Time</th>
			<th>Audio</th>
			<th>Author</th>
			<th>Text</th>
		</tr>
";

//sql to call op from thread
$sql = "SELECT * FROM postList WHERE postId = '" . $opId ."';";

//declare arrays for use to make page look nice
$postIdArray = array();
$opIdArray = array();
$postTimeArray = array();
$postTextArray = array();
$fileLinkArray = array();
$userSubmittedArray = array();

foreach($db->query($sql) as $row)
{
	array_push($postIdArray,  $row["postId"]);
	array_push($opIdArray,  $row['opId']);
	array_push($postTimeArray,  $row['postTime']);
	array_push($postTextArray,  $row['postText']);
	array_push($fileLinkArray,  $row['fileLink']);
	array_push($userSubmittedArray,  $row['userSubmitted']);

	echo "<tr><th>"; 

	echo $row['postId'];
	echo "</th><th>";
	echo $row['postTime'];
	echo "</th><th>";
	echo "<audio controls><source src='http://quantumicar.us" . $row['fileLink'] . "' type='audio/wav'>
</audio>
</th><th>";
	echo $row['userSubmitted'];
	echo "</th><th>";
	echo $row['postText'];
	echo "</th></tr>";
}


//sql to call everything (except op)from the thread. 
$sql = "SELECT * FROM postList WHERE opId = '" . $opId ."';";

//run sql and put errything into arrays.
foreach($db->query($sql) as $row)
{
	array_push($postIdArray,  $row["postId"]);
	array_push($opIdArray,  $row['opId']);
	array_push($postTimeArray,  $row['postTime']);
	array_push($postTextArray,  $row['postText']);
	array_push($fileLinkArray,  $row['fileLink']);
	array_push($userSubmittedArray,  $row['userSubmitted']);

	echo "<tr><th>"; 

	echo $row['postId'];
	echo "</th><th>";
	echo $row['postTime'];
	echo "</th><th>";
	echo "<audio controls><source src='http://quantumicar.us" . $row['fileLink'] . "' type='audio/wav'>
</audio>
</th><th>";
	echo $row['userSubmitted'];
	echo "</th><th>";
	echo $row['postText'];
	echo "</th></tr>";
}

echo "</table>";



require 'footer.php';

?>