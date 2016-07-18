<?php

//header
require "header.php";

echo "
<form action = 'uploadFile.php' method = 'post' enctype='multipart/form-data' width = '30%' align='center'>
Select Audio File to upload:
<input type='file' name = 'uploadFile' id='uploadFile'><br>
Title <input type='text' name = 'title'><br>
<input type='submit' value='Upload File' name = 'submit'><br>
</form>
";
//footer
require "footer.php";

?>
