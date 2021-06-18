<?php

$host = 'localhost';
$user = 'root';
$pw = 'root';
$dbname = 'articles';

$conn = new mysqli($host, $user, $pw, $dbname);

if($conn->connect_error){
	die("Connection failed: ".$conn->connect_error);
}

$sql = "DROP TABLE articles";

if($conn->query($sql) === TRUE){
	echo "drop success";
}
else{
	echo "drop fail: ".$conn->error;
}

$conn->close();
?>
