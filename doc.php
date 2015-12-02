<?php 

$url = file_get_contents("http://www.zeopean.com");
$fileContent = getWordDocument($url);

var_dump($fileContent);

 ?>