<?php
//read from crawl.txt and put it into MySQL
$host = 'localhost';
$user = 'root';
$pw = 'root';
$dbname = 'articles';

$conn = new mysqli($host, $user, $pw, $dbname);
if($conn->connect_error){
	die("Connection failed: ".$conn->connect_error);
}
//mysqli_set_charset($conn, 'utf8mb4');

$fp = fopen("crawl.txt", "r") or die("Can't open file!");
// [ date | page | row | views | title | url | imgurl | maintext ]
while(!feof($fp)){

	$buffer = explode("|", fgets($fp));

	$date = $buffer[0];
	$page = (int)$buffer[1];
	$row = (int)$buffer[2];
	$views = (int)str_replace(',', '', iconv_substr($buffer[3], 3));
	$title = addslashes($buffer[4]);
	$url = $buffer[5];
	$imgurl = $buffer[6];
	$maintext = addslashes($buffer[7]);

	echo $title."<br>";
	$sql = "INSERT INTO articles (date, page, row, views, title, url, imgurl, maintext)
					VALUES ('".$date."','".$page."','".$row."','".$views."','".$title."','".$url."','".$imgurl."','".$maintext."')";

	if($conn->query($sql) === TRUE){
		echo "insert successful<br>";
	}
	else{
		echo $conn->error."<br>";
	}
}
$conn->close();
fclose($fp);
?>
