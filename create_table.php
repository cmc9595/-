<?php

$host = 'localhost';
$user = 'root';
$pw = 'root';
$dbname = 'articles';

$conn = new mysqli($host, $user, $pw, $dbname);

if($conn->connect_error){
	die("Connection failed: ".$conn->connect_error);
}

$sql = "CREATE TABLE articles(
		date DATE NOT NULL,
		page INT(2) NOT NULL,
		row INT(2) NOT NULL,
		views INT(8),
		title TEXT,
		url TEXT,
		imgurl TEXT,
		maintext TEXT
		)";

if($conn->query($sql) === TRUE){
	echo "Table Articles create success";
}
else{
	echo "create fail: ".$conn->error;
}

$conn->close();
?>
